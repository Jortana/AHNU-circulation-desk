<?php
    session_start();

    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试',
    ];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $book_ID = $_POST['book_ID'];
    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    $user_ID = $info_arr[1];
    $today = date('Y-m-d H:i:s');

    $check_query = "select penalty, clear from bar_borrow where user_ID = $user_ID and book_ID = $book_ID and act_date = '1000-01-01 00:00:00';";
    $check_result = $conn->query($check_query);
    
    // 小的安全检测
    if ($check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        if ($row['penalty'] != 0 && $row['clear'] != 1) {
            // 如果是这种情况一般不是正常用户的提交
            // 直接退出就行了
            exit();
        }
    } else {
        // 其实是不正当提交
        $response['msg'] = '数据库错误1';
        echo json_encode($response);
        exit();
    }

    // 正常请求的流程
    $query = "update bar_borrow set act_date = '$today' where user_ID = $user_ID and book_ID = $book_ID and act_date = '1000-01-01 00:00:00';";
    $result = $conn->query($query);
    if ($result) {
        $book_query = "update bar_book set book_borrow = 0 where book_ID = $book_ID;";
        $book_result = $conn->query($book_query);
        if ($book_result) {
            $response['success'] = '1';
            $response['code'] = '0';
            $response['msg'] = '还书成功';

            echo json_encode($response);
            exit();
        } else {
            $response['msg'] = '数据库错误';
            echo json_encode($response);
            exit();
        }
    } else {
        $response['msg'] = '数据库错误';
        echo json_encode($response);
        exit();
    }
?>