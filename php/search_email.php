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
    $query = "select user_ID, stu_number, user_pass from bar_user where user_email = '$email';";
    $result = $conn->query($query);
    if ($result->num_rows <= 0) {
        $response['code'] = '-2';
        $response['msg'] = '邮箱未注册';

        echo json_encode($response);
        exit();
    }

    $row = $result->fetch_assoc();
    $user_ID = $row['user_ID'];
    $token = md5($user_ID . $row['stu_number'] . $row['user_pass']);
    $url = "http://www.jortana.top/phpwork/AHNU-circulation-desk/account/resetpass.html?email=$email&token=$token";
    $now = date('Y-m-d H:i:s');
    $update = "update bar_user set get_pass_time = '$now' where user_ID = $user_ID";
    $result = $conn->query($update);
    if ($result == FALSE) {
        echo json_encode($response);
        exit();
    }

    $title = "AHNU Circulation Desk重置密码";
    $content = "<style>
                    p {
                        font-family: \"Microsoft YaHei\", serif;
                    }
                </style>
                <p>请点击链接重置密码：<br>
                    <a href=\"$url\">$url</a><br>
                    如果不是本人操作请忽略
                </p>";
    $mailer = new NetEastMailer();
    $send_result = $mailer->send($email, $title, $content);
    if ($send_result == FALSE) {
        $response['code'] = '-12';
        $response['msg'] = '邮件发送失败';
        echo json_encode($response);
        exit();
    }
    
    $response['success'] = '1';
    $response['code'] = '0';
    $response['msg'] = '邮件发送成功';
    echo json_encode($response);
    exit();
?>