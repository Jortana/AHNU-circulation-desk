<?php
    function db_connect() {
        @$conn = new mysqli('localhost', 'phpwork', 'upld', 'phpwork_bandr');
        if ($conn->connect_error) {
            throw new Exception("Could not connect to database.");
        } else {
            $conn->set_charset("utf8");
            return $conn;
        }
    }
    
    // 查询是否有重复的学号
    function is_number_exist($conn, $number) {
        $query = "select stu_number from bar_user where stu_number = ?";
        return is_xx_exist($conn, $number, $query);
    }

    //查询是否有重复的手机号
    function is_phone_exist($conn, $phone) {
        $query = "select user_phone from bar_user where user_phone = ?";
        return is_xx_exist($conn, $phone, $query);
    }
    
    //查询是否有重复的邮箱
    function is_email_exist($conn, $email) {
        $query = "select user_email from bar_user where user_email = ?";
        return is_xx_exist($conn, $email, $query);
    }

    //查询重复的通用接口
    function is_xx_exist($conn, $value, $query) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
?>