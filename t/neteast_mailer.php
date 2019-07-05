<?php
    require("../lib/class-phpmailer.php");
    require("../lib/class-smtp.php");

    class NetEastMailer
    {
        public static $HOST = "smtp.163.com";
        public static $PORT = 465;
        public static $SMTP = 'ssl';
        public static $CHARSET = 'UTF-8';

        private static $USERNAME = '**********';
        private static $PASSWORD = '**********';
        private static $NICKNAME = 'AHNU Circulation Desk';

        public function __construct($debug = false)
        {
            $this->mailer = new PHPMailer();
            $this->mailer->isSMTP();
        }

        public function getMailer()
        {
            return $this->mailer;
        }

        private function loadConfig()
        {
            // 服务的设置
            $this->mailer->SMTPAuth = true;
            $this->mailer->Host = self::$HOST;
            $this->mailer->Port = self::$PORT;
            $this->mailer->SMTPSecure = self::$SMTP;
            // 账号的设置
            $this->mailer->Username = self::$USERNAME;
            $this->mailer->Password = self::$PASSWORD;
            $this->mailer->From = self::$USERNAME;
            $this->mailer->FromName = self::$NICKNAME;
            // 内容设置
            $this->mailer->isHTML(true);
            $this->mailer->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端"; 
            $this->mailer->CharSet = self::$CHARSET;
        }

        // 发送附件的功能，暂时用不到
        public function addFile($path)
        {
            $this->mailer->addAttachment($path);
        }

        public function send($email, $title, $content)
        {
            $this->loadConfig();
            $this->mailer->addAddress($email);
            $this->mailer->Subject = $title;
            $this->mailer->Body = $content;
            return (bool)$this->mailer->send();
        }
    }
?>