<?php
    session_start();
    
    $response = [
        'logged' => false,
        // 默认-1代表未连接数据库
        'info' => ''
    ];

    if (isset($_SESSION['info'])) {
        $response['logged'] = true;
        $response['info'] = $_SESSION['info'];
    }

    echo json_encode($response);
    exit();
?>