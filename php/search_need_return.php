<?php
    session_start();

    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试',
        'data' => [],
    ];
    
    // 误访问也写出一个结果
    if (!isset($_SESSION['info'])) {
        echo '未登录';
        exit();
    }

    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    $user_ID = $info_arr[1];
    
    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $query = "select * from bar_borrow where user_ID = $user_ID and act_date = '1000-01-01 00:00:00';";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data_item = [];
        $book_ID = $row['book_ID'];
        $data_item['book_ID'] = $row['book_ID'];
        $data_item['br_date'] = $row['br_date'];
        $data_item['exp_date'] = $row['exp_date'];

        // 拿到exp_date之后先验证一下是否需要更新了
        $exp_date = $row['exp_date'];
        $over_info = check_over_penalty($conn, $exp_date, $book_ID, $user_ID);
        if ($over_info == -1) {
            $response['msg'] = '数据库错误';
            echo json_encode($response);
            exit();
        } else if ($over_info == 0) {
            $data_item['over_days'] = 0;
            $data_item['penalty'] = 0;
        } else {
            $data_item['over_days'] = $over_info['over_days'];
            $data_item['penalty'] = $over_info['penalty'];            
        }


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