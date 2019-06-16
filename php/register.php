<?php
    require('../lib/db_fns.php');

    $result = [
        //默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试'
    ];
    
    //判断是否填0的恶意表单
    //数据库查询重复的函数可能不能判断"0"项
    foreach ($_POST as $key => $value) {
        if ($key == 'password') {
            continue;
        }

        if ($value == '0') {
            $result['code'] = '-2';
            $result['msg'] = '没有可以填0的项';
            echo json_encode($result);
            exit();
        } 
    }

    $number = $_POST['stu-number'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $pass = sha1($_POST['password']);
    $name = $_POST['name'];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($result);
        exit();
    }

    //查询有无重复学号、手机号、邮箱
    if (is_number_exist($conn, $number)) {
        if ($result['code'] !== '-1') {
            $result['code'] .= '|1';
            $result['msg'] .= '|学号已存在';
        } else {
            $result['code'] = '1';
            $result['msg'] = '学号已存在';
        }
    }

    if (is_phone_exist($conn, $phone)) {
        if ($result['code'] !== '-1') {
            $result['code'] .= '|2';
            $result['msg'] .= '|手机号已存在';
        } else {
            $result['code'] = '2';
            $result['msg'] = '手机号已存在';
        }
    }

    if (is_email_exist($conn, $email)) {
        if ($result['code'] !== '-1') {
            $result['code'] .= '|3';
            $result['msg'] .= '|电子邮箱已存在';
        } else {
            $result['code'] = '3';
            $result['msg'] = '电子邮箱已存在';
        }
    }

    //若其中有重复项就返回错误代码和信息并退出脚本
    if ($result['code'] !== '-1') {
        echo json_encode($result);
        $conn->close();
        exit();
    }

    //检查无问题后开始写入数据库
    $query = "insert into bar_user
            (stu_number, user_phone, user_email, user_pass)
            values
            (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $number, $phone, $email, $pass);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        //0代表插入成功
        $result['code'] = '0';
        $result['msg'] = '注册成功';
    }
    
    echo json_encode($result);
    $conn->close();
    exit();
?>