<?php
    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试',
        'content' => '',
    ];

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $query = "select pub_content from bar_public order by pub_time desc limit 1;";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['success'] = '1';
        $response['code'] = '0';
        $response['content'] = $row['pub_content'];
    }

    echo json_encode($response);
    exit();
?>