<?php
    function db_connect() {
        @$conn = new mysqli('localhost', 'phpwork', '**********', 'phpwork_bandr');
        if ($conn->connect_error) {
            throw new Exception("Could not connect to database.");
        } else {
            $conn->set_charset("utf8");
            return $conn;
        }
    }
?>