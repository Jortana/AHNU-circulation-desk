<?php
    $response = $_POST['data'];
    $post = http_build_query(
        array (
            'response' => $response,
            'secret' => '6Leum6sUAAAAAMk3NSWbsTtMdym-5yFuyHh9uh0o',
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
    $serverResponse = @file_get_contents('https://www.recaptcha.net/recaptcha/api/siteverify', false, $context);
    if (!$serverResponse) {
        exit('Failed to validate Recaptcha');
    }
    $result = json_decode($serverResponse);
    if (!$result -> success) {
        exit('Invalid Recaptcha');
    }
    exit('Recaptcha Validated');
?>