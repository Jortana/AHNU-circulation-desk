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

    // 更新借阅信息
    $clear_query = "update bar_borrow set act_date = '$today', clear = 1
                    where user_ID = $user_ID and book_ID = $book_ID and act_date = '1000-01-01 00:00:00';";
    $clear_result = $conn->query($clear_query);
    if ($clear_result == FALSE) {
        $response['msg'] = '数据库错误';
        echo json_encode($response);
        exit();
    } else {
        // 更新图书信息
        $book_query = "update bar_book set book_borrow = 0 where book_ID = $book_ID;";
        $book_result = $conn->query($book_query);
        if ($clear_result == FALSE) {
            $response['msg'] = '数据库错误';

            echo json_encode($response);
            exit();
        } else {
            $response['seccess'] = '1';
            $response['code'] = '0';
            $response['msg'] = '付费和付款成功';
            
            echo json_encode($response);
            exit();
        }
    }
?>