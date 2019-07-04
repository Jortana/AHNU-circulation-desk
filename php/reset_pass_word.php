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
    $new_password = sha1($_POST['new_password']);
    $query = "update bar_user set user_pass = '$new_password' where user_email = '$email';";
    $result = $conn->query($query);
    if ($result == FALSE) {
        $response['msg'] = '数据库错误';
        echo json_encode($response);
        exit();
    }

    $response['success'] = '1';
    $response['code'] = '0';
    $response['msg'] = '修改成功，请登录';
    echo json_encode($response);
    exit();
?>