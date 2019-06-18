<?php
    session_start();

    if (isset($_SESSION['info'])) {
        unset($_SESSION['info']);
        echo true;
        exit();
    } else {
        echo false;
        exit();
    }
?>