<?php
    session_start();
    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试',
        'count' => 0,
        'data' => [],
    ];

    // 安全验证
    if (!isset($_SESSION['info'])) {
        $response['code'] = '-5';
        $response['msg'] = '用户未登录';
        echo json_encode($response);
        exit();
    }

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    $user_ID = $info_arr[1];

    $query = "select * from bar_borrow where user_ID = $user_ID and act_date <> '1000-01-01 00:00:00';";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data_item = [];
        $book_ID = $row['book_ID'];
        $data_item['book_ID'] = $row['book_ID'];
        $data_item['br_date'] = $row['br_date'];
        $data_item['act_date'] = $row['act_date'];
        $data_item['over_days'] = $row['over_days'];
        $data_item['penalty'] = $row['penalty'];

        // 查询书目信息以显示
        $info_query = "select book_name, book_author, book_cate, book_pub from bar_book where book_ID = $book_ID;";
        $info_result = $conn->query($info_query);
        $info = $info_result->fetch_assoc();
        
        $data_item['name'] = $info['book_name'];
        $data_item['author'] = $info['book_author'];
        $data_item['cate'] = $info['book_cate'];
        $data_item['pub'] = $info['book_pub'];
        
        array_push($response['data'], $data_item);
        $response['success'] = '1';
        $response['code'] = 0;
        $response['msg'] = '查询成功';
    }
    
    echo json_encode($response);
?>