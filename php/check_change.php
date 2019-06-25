<?php
    session_start();
    
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
    
    $new_phone = $_POST['new_phone'];
    $new_email = $_POST['new_email'];
    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    $user_ID = $info_arr[1];

    $select_query = "select user_phone, user_email from bar_user where user_ID <> $user_ID
                    union select mng_phone, mng_email from bar_manager;";
    $select_result = $conn->query($select_query);
    if ($select_result->num_rows > 0) {
        while ($row = $select_result->fetch_assoc()) {
            if ($row['user_phone'] == $new_phone || $row['user_email'] == $new_email) {
                if ($row['user_phone'] == $new_phone && $row['user_email'] == $new_email) {
                    $response['code'] = '2|3';
                    $response['msg'] = '手机号和电子邮箱重复';
                } elseif ($row['user_phone'] == $new_phone) {
                    $response['code'] = '2';
                    $response['msg'] = '手机号重复';
                } else {
                    $response['code'] = '3';
                    $response['msg'] = '电子邮箱重复';
                }
                echo json_encode($response);
                exit();
            }
        }
    } else {
        $response['msg'] = '数据库错误';
        echo json_encode($response);
        exit();
    }

    // 无重复,更新信息
    $update_query = "update bar_user set user_phone = ?, user_email = ? where user_ID = $user_ID";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ss', $new_phone, $new_email);
    $result = $stmt->execute();
    if ($result == TRUE) {
        $response['success'] = '1';
        $response['code'] = 0;
        $response['msg'] = '修改成功';
    } else {
        $response['msg'] = '数据库错误';
    }
    
    echo json_encode($response);
    exit();
?>