<?php
    session_start();

    if (isset($_SESSION['stu_number'])) {
        echo $_SESSION['stu_number'];
    } else {
        echo 0;
    }
?>