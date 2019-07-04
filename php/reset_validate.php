<?php
    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试'
    ];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $email = $_POST['email'];
    $token = $_POST['token'];

    $search = "select user_ID, stu_number, user_pass, get_pass_time from bar_user where user_email = '$email';";
    $result = $conn->query($search);
    if ($result->num_rows <= 0) {
        $response['code'] = '-1';
        $response['msg'] = '非法请求';

        echo json_encode($response);
        exit();
    }

    $row = $result->fetch_assoc();

    if (check_token($row, $token) == FALSE) {
        $response['code'] = '-1';
        $response['msg'] = '非法请求';
        echo json_encode($response);
        exit();
    }

    $get_time = strtotime($row['get_pass_time']);
    if (check_time($get_time) == FALSE) {
        $response['code'] = '-10';
        $response['msg'] = '链接已过期';
        echo json_encode($response);
        exit();
    }

    $response['success'] = '1';
    $response['code'] = '0';
    $response['msg'] = '等待修改密码';
    echo json_encode($response);
    exit();
    
    function check_time($get_time) {
        $now = strtotime(date('Y-m-d H:i:s'));
        $diff = $now - $get_time;
        if ($diff > 1800) {
            return FALSE;
        }
        return TRUE;
    }

    function check_token($row, $token) {
        $real_token = md5($row['user_ID'] . $row['stu_number'] . $row['user_pass']);
        if ($real_token != $token) {
            return FALSE;
        }
        return TRUE;
    }
?>