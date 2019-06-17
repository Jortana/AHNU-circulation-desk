<?php
    session_start();

    $respond = [
        $respond['success'] = '0'
    ];

    $stu_number = $_POST['stu_number'];
    $pass = sha1($_POST['pass']);

    $conn = new mysqli('localhost', 'phpwork', 'upld', 'phpwork_bandr');
    // $query = "select * from bar_user where stu_number = ? and user_pass = ?";
    $query = "select * from bar_user where stu_number = ".$stu_number." and user_pass = "."'".$pass."'";
    // $query = "select * from bar_user where stu_number = 16110800000 and user_pass = 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'";
    $r = $conn->query($query);
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param('ss', $stu_number, $pass);
    // $stmt->bind_result($id, $nb, $ph, $em, $ps);
    // $stmt->execute();
    // $stmt->fetch();

    if ($r) {
        $respond['success'] = '1';
    }

    $_SESSION['stu_number'] = $stu_number;

    echo json_encode($respond);
?>