<?php
    $conn = new mysqli('localhost', 'phpwork', 'upld', 'phpwork_bandr');
    mysqli_query($conn,'set names utf8');
    $query = "update bar_manager set mng_name = '测试管理员';";
    $result = $conn->query($query);
    print_r($result.'\n');
?>