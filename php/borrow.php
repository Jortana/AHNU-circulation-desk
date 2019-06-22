<?php
    session_start();
    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试',
    ];

    // 应该可以不要这个验证,因为在前端已经验证,未登录用户不会发送借书的请求
    if (!isset($_SESSION['info'])) {
        $response['code'] = '-5';
        $response['msg'] = '用户未登录';
        echo json_encode($response);
        exit();
    }

    $book_ID = $_POST['ID'];
    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    $user_ID = $info_arr[1];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $update_query = "update bar_book
            set book_borrow = 1, book_times = book_times + 1
            where book_ID = $book_ID";
    $update_result = $conn->query($update_query);
    if ($update_result == TRUE) {
        $br_date = date('Y-m-d H:i:s');
        $exp_date = date("Y-m-d", strtotime("+60 days"))." 23:59:59";
        $act_date = '1000-01-01 00:00:00';
        $insert_query = "insert into bar_borrow
                        values
                        ($user_ID, $book_ID, '$br_date', '$exp_date', '$act_date', 0, 0);";
        $insert_result = $conn->query($insert_query);
        if ($insert_result == TRUE) {
            $response['success'] = '1';
            $response['code'] = '0';
            $response['msg'] = '借书成功';
            echo json_encode($response);
            exit();
        } else {
            $response['msg'] = '数据库错误';
            echo json_encode($response);
            exit();
        }
    } else {
        $response['code'] = '-7';
        $response['msg'] = '无图书信息';
        echo json_encode($response);
        exit();
    }
?>