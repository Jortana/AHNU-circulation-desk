create table `bar_public`
(
    `pub_ID` int unsigned not null primary key auto_increment comment '公告编号',
    `pub_content` text not null comment '公告内容',
    `pub_mng` int unsigned not null comment '发布管理员',
    `pub_time` datetime not null comment '发布时间'

    /* foreign key (`pub_mng`) references `bar_mng`(`mng_ID`) */
) engine = InnoDB charset=utf8 comment = '公告表';

/* insert into `bar_manager` values (1, '测试管理员', 'admin@test.com', '17700000000', 'd033e22ae348aeb5660fc2140aec35850c4da997'); */