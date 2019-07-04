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

    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    $user_ID = $info_arr[1];
    
    // 安全检测
    if ($user_ID == '') {
        $response['code'] = '-5';
        $response['msg'] = '用户未登录';
        echo json_encode($response);
        exit();
    }

    $new = sha1($_POST['newpass']);
    $query = "update bar_user set user_pass = '$new' where user_ID = $user_ID;";
    $result = $conn->query($query);

    if ($result == FALSE) {
        $response['msg'] = '数据库错误，请联系管理员';
        echo json_encode($response);
        exit();
    }

    $response['success'] = '1';
    $response['code'] = '0';
    $response['msg'] = '修改成功，请重新登录';
    unset($_SESSION['info']);
    echo json_encode($response);
    exit();
?>