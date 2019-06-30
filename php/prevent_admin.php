<?php
    session_start();

    $response = [
        'login' => '0',
        'admin' => '0',
    ];
    if (!isset($_SESSION['info'])) {
        echo json_encode($response);
        exit();
    }

    $user_info = $_SESSION['info'];
    $info_arr = explode('|', $user_info);
    if ($info_arr[2] == 'admin') {
        $response['login'] = '1';
        $response['admin'] = '1';
    } else {
        $response['login'] = '1';
    }

    echo json_encode($response);
    exit();
?>