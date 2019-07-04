<?php
    session_start();

    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试',
        'account_info' => [],
    ];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    // 小检测
    if (!isset($_SESSION['info'])) {
        $response['msg'] = '未登录';
        echo json_encode($response);
        exit();
    }
    
    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    $user_ID = $info_arr[1];
    $account_info = [];

    // 先找user表里的信息
    $user_query = "select stu_number, user_phone, user_email from bar_user where user_ID = $user_ID;";
    $user_result = $conn->query($user_query);
    if ($user_result->num_rows > 0) {
        $row = $user_result->fetch_assoc();
        $account_info['number'] = $row['stu_number'];
        $account_info['phone'] = $row['user_phone'];
        $account_info['email'] = $row['user_email'];
    } else {
        $response['msg'] = '数据库错误(user)';
        echo json_encode($response);
        exit();
    }
    
    // 再找reader表里的信息
    $reader_query = "select * from bar_reader where user_ID = $user_ID;";
    $reader_result = $conn->query($reader_query);
    if ($reader_result->num_rows > 0) {
        $row = $reader_result->fetch_assoc();
        $account_info['name'] = $row['user_name'];
        $account_info['sex'] = $row['user_sex'];
        $account_info['grade'] = $row['user_grade'];
        $account_info['pro'] = $row['user_pro'];
        $account_info['pun'] = $row['pun_time'];
        $account_info['inter'] = $row['inter'];
    } else {
        $response['msg'] = '数据库错误(reader)';
        echo json_encode($response);
        exit();
    }

    // 还有borrow表里的信息
    $borrow_query = "select over_days from bar_borrow where user_ID = $user_ID and act_date <> '1000-01-01 00:00:00';";
    $borrow_result = $conn->query($borrow_query);
    // 这里如果select语句出错,用户会看到借书次数为0,且没有报错,待解决
    if ($borrow_result->num_rows > 0) {
        $account_info['borrow_time'] = $borrow_result->num_rows;
    } else {
        $account_info['borrow_time'] = 0;
    }
    
    $response['success'] = '1';
    $response['code'] = '0';
    $response['msg'] = '查询成功';
    $response['account_info'] = $account_info;
    echo json_encode($response);    
?>