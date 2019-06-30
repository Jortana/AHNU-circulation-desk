<?php
    session_start();

    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试'
    ];

    // $login_id = str_replace(' ','',$_POST['login-id']);
    $login_id = $_POST['login-id'];
    $password = sha1($_POST['password']);

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $query = "select user_ID, user_pass from bar_user where user_email = ? or user_phone = ? or stu_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $login_id, $login_id, $login_id);
    $stmt->bind_result($user_ID, $real_pass);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    if ($real_pass) {
        $isvalid = validate($real_pass, $password, $response);
        if ($isvalid) {
            // 密码正确,写入$_SESSION['info'];
            user_write_to_session($conn, $user_ID);
        }
    } else {
        $query = "select mng_ID, mng_pass from bar_manager where mng_email = ? or mng_phone = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $login_id, $login_id);
        $stmt->bind_result($mng_ID, $real_pass);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        if ($real_pass) {
            $isvalid = validate($real_pass, $password, $response);
            if ($isvalid) {
                $response['t'] = $mng_ID . 'admin';
                // 密码正确,写入$_SESSION['info'];
                admin_write_to_session($conn, $mng_ID);
            }
        }
    }

    echo json_encode($response);
    exit();

    function validate($real_pass, $password, &$response) {
        // 验证密码是否正确的函数
        if ($real_pass === $password) {
            $response['success'] = '1';
            $response['code'] = '0';
            $response['msg'] = '登录成功';

            return true;
        } else {
            $response['code'] = '-3';
            $response['msg'] = '用户名或密码错误';

            return false;
        }
    }

    function user_write_to_session($conn, $user_ID) {
        $query = "select user_name from bar_reader where user_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_ID);
        $stmt->bind_result($user_name);
        $stmt->execute();
        $stmt->fetch();

        $_SESSION['info'] = $user_name.'|'.$user_ID;
    }

    function admin_write_to_session($conn, $mng_ID) {
        $query = "select mng_name from bar_manager where mng_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $mng_ID);
        $stmt->bind_result($mng_name);
        $stmt->execute();
        $stmt->fetch();

        $_SESSION['info'] = $mng_name.'|'.$mng_ID.'|'.'admin';
    }
?>