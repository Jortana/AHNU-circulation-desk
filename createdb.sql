create database phpwork_bandr;
use phpwork_bandr;

create table `bar_user`
(
    `user_ID` int unsigned not null auto_increment primary key comment '用户编号',
    `user_pass` varchar(255) not null comment '密码',

    unique `uniq_uid` (`user_ID`)
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
    `book_cate` varchar(20) not null comment '分类',
    `book_date` datetime not null comment '入库时间',

    unique `uniq_bid` (`book_ID`),
    index `idx_bname` (`book_name`),
    index `idx_author` (`book_author`),
    index `idx_cate` (`book_cate`)
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

    primary key (`user_ID`, `book_ID`, `br_date`),
    foreign key (`user_ID`) references `bar_user`(`user_ID`),
    foreign key (`book_ID`) references `bar_book`(`book_ID`),

    unique `uniq_uid` (`user_ID`)
) engine = InnoDB charset=utf8 comment = '图书借还信息表';

create table `bar_manager`
(
    `mng_ID` int unsigned not null auto_increment primary key comment '管理员编号',
    `mng_name` char(20) not null comment '管理员姓名',
    `mng_phone` char(12) not null comment '管理员电话'
) engine = InnoDB charset=utf8 comment = '管理员表';