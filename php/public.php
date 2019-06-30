<?php
    session_start();

    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败',
    ];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $content = $_POST['content'];
    $mng_info = $_SESSION['info'];
    $info_arr = explode('|', $mng_info);
    $mng_ID = $info_arr[1];
    $datetime = date('Y-m-d H:i:s');
    $content = strip_tags($content);
    $content = str_replace("\n", "<br>", $content);

    // 安全检测
    if ($info_arr[2] != 'admin') {
        $response['msg'] = '你没有权限';
        echo json_encode($response);
        exit();
    }

    $query = "insert into bar_public (pub_content, pub_mng, pub_time) values ('$content', $mng_ID, '$datetime');";
    $result = $conn->query($query);
    if ($result) {
        $response['success'] = '1';
        $response['code'] = '0';
        $response['msg'] = '发布成功';
    } else {
        $response['msg'] = '数据库错误，发布失败';
    }

    echo json_encode($response);
    exit();
?>