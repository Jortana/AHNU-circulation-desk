<?php
    require('../lib/db_fns.php');

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试'
    ];
    
    // 判断是否填0的恶意表单
    // 数据库查询重复的函数可能不能判断"0"项
    foreach ($_POST as $key => $value) {
        // 去除空格
        $value = str_replace(' ', '', $value);
        if ($key == 'password') {
            continue;
        }

        if ($value == '0') {
            $response['code'] = '-2';
            $response['msg'] = '没有可以填0的项';
            echo json_encode($response);
            exit();
        } 
    }

    $number = $_POST['stu-number'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $pass = sha1($_POST['password']);
    $name = $_POST['name'];
    $sex = $_POST['sex'];
    $grade = $_POST['grade'];
    $pro = $_POST['pro'];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    // 查询有无重复学号、手机号、邮箱
    if (is_number_exist($conn, $number)) {
        if ($response['code'] !== '-1') {
            $response['code'] .= '|1';
            $response['msg'] .= '|学号已存在';
        } else {
            $response['code'] = '1';
            $response['msg'] = '学号已存在';
        }
    }

    if (is_phone_exist($conn, $phone)) {
        if ($response['code'] !== '-1') {
            $response['code'] .= '|2';
            $response['msg'] .= '|手机号已存在';
        } else {
            $response['code'] = '2';
            $response['msg'] = '手机号已存在';
        }
    }

    if (is_email_exist($conn, $email)) {
        if ($response['code'] !== '-1') {
            $response['code'] .= '|3';
            $response['msg'] .= '|电子邮箱已存在';
        } else {
            $response['code'] = '3';
            $response['msg'] = '电子邮箱已存在';
        }
    }

    // 若其中有重复项就返回错误代码和信息并退出脚本
    if ($response['code'] !== '-1') {
        echo json_encode($response);
        $conn->close();
        exit();
    }

    // 检查无问题后开始写入数据库
    // 先写bar_user表
    $query = "insert into bar_user
            (stu_number, user_phone, user_email, user_pass)
            values
            (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $number, $phone, $email, $pass);
    $stmt->execute();

    if ($stmt->affected_rows <= 0) {
        // -3代表插入bar_user出错
        $response['code'] = '-3';
        $response['msg'] = '注册失败';

        echo json_encode($response);
        $conn->close();
        exit();
    }

    // 再写bar_reader表
    $pri = $conn->insert_id;
    $query = "insert into bar_reader
            (user_ID, user_name, user_sex, user_grade, user_pro)
            values
            (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $pri, $name, $sex, $grade, $pro);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response['success'] = '1';
        //0代表插入成功
        $response['code'] = '0';
        $response['msg'] = '注册成功';
    } else {
        // -4代表插入bar_reader失败
        $response['code'] = '-4';
        $response['msg'] = '注册失败';
    }
    
    echo json_encode($response);
    $conn->close();
    exit();
?>