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

    if (strpos($login_id, '@')) {
        // 用邮箱登录
        $query = "select user_ID, user_pass from bar_user where user_email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $login_id);
        $stmt->bind_result($user_ID, $real_pass);
        $stmt->execute();
        $stmt->fetch();
        if ($real_pass) {
            // 用户存在,验证密码,并直接写入$reault
            $isvalid = validate($real_pass, $password, $response);
            if ($isvalid) {
                // 密码正确,写入$_SESSION['info'];
                write_to_session($conn, $user_ID);
            };

            // 无论正确与否都可以输出并退出脚本了
            echo json_encode($response);
            $conn->close();
            exit();
        }
    } else {
        // 用学号或手机号
        // 先验证是否为学号
        $query = "select user_ID, user_pass from bar_user where stu_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $login_id);
        $stmt->bind_result($user_ID, $real_pass);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        
        if ($real_pass) {
            // 确实是用学号登录,用户存在
            $isvalid = validate($real_pass, $password, $response);
            if ($isvalid) {
                // 密码正确,写入$_SESSION['info'];
                write_to_session($conn, $user_ID);
            };

            // 无论正确与否都可以输出并退出脚本了
            echo json_encode($response);
            $conn->close();
            exit();
        } else {
            // 再验证是否为手机号
            $query = "select user_ID, user_pass from bar_user where user_phone = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $login_id);
            $stmt->bind_result($user_ID, $real_pass);
            $stmt->execute();
            $stmt->fetch();
            if ($real_pass) {
                // 确实是用手机号登录,用户存在
                $isvalid = validate($real_pass, $password, $response);
                if ($isvalid) {
                    // 密码正确,写入$_SESSION['info'];
                    write_to_session($conn, $user_ID);
                };
    
                // 无论正确与否都可以输出并退出脚本了
                echo json_encode($response);
                $conn->close();
                exit();
            } else {
                // 用户不存在
                $response['code'] = '-2';
                $response['msg'] = '用户不存在';

                echo json_encode($response);
                $conn->close();
                exit();
            }
        }
    }

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

    function write_to_session($conn, $user_ID) {
        $query = "select user_name from bar_reader where user_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_ID);
        $stmt->bind_result($user_name);
        $stmt->execute();
        $stmt->fetch();

        $_SESSION['info'] = $user_name.'|'.$user_ID;
    }
?>