alter table `bar_user` add `stu_number` char(12) comment '学号' after `user_ID`;
alter table `bar_user` add index `idx_snumber` (`stu_number`);