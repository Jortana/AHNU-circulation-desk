<?php
    $response = [
        'success' => '0',
        // 这里的-1代表未连接reCAPTCHA
        'code' => '-1',
        'msg' => '连接reCAPTCHA服务失败'
    ];

    $data = $_POST['data'];
    $post = http_build_query(
        array (
            'response' => $data,
            'secret' => '***',
            'remoteip' => $_SERVER['REMOTE_ADDR']
        )
    );
    $opts = array('http' => 
        array (
            'method' => 'POST',
            'header' => 'application/x-www-form-urlencoded',
            'content' => $post
        )
    );
    $context = stream_context_create($opts);
    $server_response = @file_get_contents('https://www.recaptcha.net/recaptcha/api/siteverify', false, $context);
    if (!$server_response) {
        echo json_encode($response);
        exit();
    }
    $result = json_decode($server_response);
    if (!$result->success) {
        $response['code'] = '-12';
        $response['msg'] = '非正常登录';
    } else {
        $response['success'] = '1';
        $response['code'] = '0';
        $response['msg'] = 'reCAPTCHA验证成功';
    }
    echo json_encode($response);
    exit();
?>
