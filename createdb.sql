create database phpwork_bandr;
use phpwork_bandr;

create table `bar_user`
(
    `user_ID` int unsigned not null auto_increment primary key comment '用户编号',
    `stu_number` char(12) not null comment '学号',
    `user_phone` char(12) not null comment '用户手机号',
    `user_email` varchar(255) not null comment '用户邮箱',
    `user_pass` char(50) not null comment '密码',
    `pass_time` datetime comment '重置密码的时间',

    unique `uniq_uid` (`user_ID`),
    index `idx_snumber` (`stu_number`),
    index `idx_uphone` (`user_phone`),
    index `idx_uemail` (`user_email`),
) engine = InnoDB charset=utf8 comment = '用户信息表';

create table `bar_reader`
(
    `user_ID` int unsigned not null comment '用户编号',
    `user_name` char(20) not null comment'用户姓名',
    `user_sex` char(4) not null comment '性别',
    `user_grade` char(10) not null comment '年级',
    `user_pro` char(50) not null comment '专业',
    `pun_time` int unsigned default 0 comment '被处罚次数',
    `inter` int default 1 comment '诚信度',

    primary key (`user_ID`),
    foreign key (`user_ID`) references `bar_user`(`user_ID`),
    
    unique `uniq_uid` (`user_ID`),
    index `idx_uname` (`user_name`),
    index `idx_grade` (`user_grade`),
    index `idx_pro` (`user_pro`)
) engine = InnoDB charset=utf8 comment = '读者信息表';

create table `bar_book`
(
    `book_ID` int unsigned not null auto_increment primary key comment '图书编号',
    `book_name` varchar(255) not null comment '书名',
    `book_author` varchar(50) not null comment '作者',
    `book_pub` varchar(50) not null comment '出版社',
    `book_cate` varchar(30) not null comment '分类',
    `book_date` datetime not null comment '入库时间',
    `book_borrow` bool not null comment '是否借出',
    `book_times` int unsigned default 0 comment '借出次数',

    unique `uniq_bid` (`book_ID`),
    index `idx_bname` (`book_name`),
    index `idx_author` (`book_author`),
    index `idx_cate` (`book_cate`),
    index `idx_borrow` (`book_borrow`),
    index `idx_times` (`book_times`)
) engine = InnoDB charset=utf8 comment = '图书信息表';

create table `bar_borrow`
(
    `user_ID` int unsigned not null comment '用户编号',
    `book_ID` int unsigned not null comment '图书编号',
    `br_date` datetime not null comment '借书时间',
    `exp_date` datetime not null comment '预期归还时间',
    `act_date` datetime comment '实际归还时间',
    `over_date` int unsigned default 0 comment '超期天数',
    `penalty` float  default 0 comment '处罚金额',
    `clear` bool  default 0 comment '是否缴纳处罚',

    primary key (`user_ID`, `book_ID`, `br_date`),
    foreign key (`user_ID`) references `bar_user`(`user_ID`),
    foreign key (`book_ID`) references `bar_book`(`book_ID`)
) engine = InnoDB charset=utf8 comment = '图书借还信息表';

create table `bar_manager`
(
    `mng_ID` int unsigned not null auto_increment primary key comment '管理员编号',
    `mng_name` char(20) not null comment '管理员姓名',
    `mng_email` varchar(255) not null comment '管理员邮箱',
    `mng_phone` char(12) not null comment '管理员电话',
    `mng_pass` char(50) not null comment '管理员密码'
) engine = InnoDB charset=utf8 comment = '管理员表';

create table `bar_public`
(
    `pub_ID` int unsigned not null primary key auto_increment comment '公告编号',
    `pub_content` text not null comment '公告内容',
    `pub_mng` int unsigned not null comment '发布管理员',
    `pub_time` datetime not null comment '发布时间',
) engine = InnoDB charset=utf8 comment = '公告表';
alter table `bar_public` add foreign key(`pub_mng`) REFERENCES `bar_manager`(`mng_ID`);