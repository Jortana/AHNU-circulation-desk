<?php
    // 本来只是想存数据库的有关函数,后来索性直接往里面放,文件又写了很多了,就没改名字了
    require("../../../../../phpworklib/db.php");
    require("../../../../../phpworklib/neteast_mailer.php");
    
    // 查询是否有重复的学号
    function is_number_exist($conn, $number) {
        $query = "select stu_number from bar_user where stu_number = ?";
        return is_xx_exist_s($conn, $number, $query);
    }

    //查询是否有重复的手机号
    function is_phone_exist($conn, $phone) {
        $query = "select user_phone from bar_user where user_phone = ?
                union select mng_phone from bar_manager where mng_phone = ?";
        return is_xx_exist_d($conn, $phone, $query);
    }
    
    //查询是否有重复的邮箱
    function is_email_exist($conn, $email) {
        $query = "select user_email from bar_user where user_email = ?
                union select mng_email from bar_manager where mng_phone = ?";
        return is_xx_exist_d($conn, $email, $query);
    }

    //查询重复的通用接口
    function is_xx_exist_s($conn, $value, $query) {
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
    
    function is_xx_exist_d($conn, $value, $query) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $value, $value);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // 确认有无超期未还书,有就返回TRUE,否则返回FALSE
    function check_over_date($conn, $user_ID) {
        $today = date('Y-m-d H:i:s');
        $query = "select * from bar_borrow where user_ID = $user_ID and exp_date < '$today' and clear = 0;";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_over_number($conn, $user_ID) {
        $query = "select * from bar_borrow where user_ID = $user_ID and act_date = '1000-01-01 00:00:00';";
        $result = $conn->query($query);
        if ($result->num_rows >= 3) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_over_penalty($conn, $exp_date, $book_ID, $user_ID) {
        $today = strtotime(date('Y-m-d H:i:s'));
        $exp_date = strtotime($exp_date);
        if ($today > $exp_date) {
            $over_info = [];
            // 超期未还
            $diff = $today - $exp_date;
            $over_days =abs(round($diff / 86400));
            $penalty = $over_days * 0.02;
            $query = "update bar_borrow set over_days = $over_days, penalty = $penalty
                    where book_ID = $book_ID and user_ID = $user_ID and act_date = '1000-01-01 00:00:00';";
            $result = $conn->query($query);
            if ($result == FALSE) {
                // 数据库错误
                return -1;
            } else {
                $over_info['over_days'] = $over_days;
                $over_info['penalty'] = $penalty;
                return $over_info;
            }
        } else {
            return 0;
        }
    }
?>