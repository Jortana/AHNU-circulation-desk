# AHNU-circulation-desk

php程序设计课程大作业——基于PHP、MySQL的web端借还书系统
## TODO

* [x] 数据库设计
* [x] 用户注册页面
* [x] 用户登陆页面
* [x] 主页面
* [x] 借书
* [x] 还书
* [x] 个人信息管理
* [x] 管理员部分
* [x] 公告
* [ ] 系统说明
* [x] reCAPTCHA验证~~验证码校验登录~~
* [x] 模糊查询
* [x] 快到期图书提醒
* [x] 个人历史纪录
* [ ] 统计模块
* [x] 通过邮箱找回密码（使用了PHPMailer，简直是救星）

项目中很多都可以用vue重构，但是因为不是很熟悉，为了不要让自己做的太慢，所以基本都是用jQuery。但是如果都用vue应该会精简很多

# 借还书系统设计思路

## 数据库设计

### 数据库概念结构设计

- 读者实体：**用户编号**、姓名、性别、年级、专业、被处罚次数、诚信度
- 图书实体：**书籍编号**、书名、作者、出版社、分类、登记日期
- 用户实体：**用户编号**、密码

### 数据库逻辑结构设计

- 读者（读者编号、姓名、性别、年级、专业、被处罚次数、诚信度）
- 图书（图书编号、书名、作者、出版社、分类、登记日期）
- 用户（用户编号、电话号码、邮箱、密码）
- 图书借还（用户编号、图书编号、借书时间、预期归还时间、实际归还时间）
- 处罚信息（用户编号、图书编号、超期天数、处罚金额）
- 管理员（管理员编号、管理员姓名、管理员电话）

### 数据库物理结构设计

#### 1.用户信息表（User）

| 名字          | 数据类型     | 是否为空 | 键  | 说明           |
| ------------- | ------------ | -------- | --- | -------------- |
| user_ID       | int          | NO       | PRI | 用户编号       |
| stu_number    | char(12)     | NO       |     | 学号           |
| user_phone    | char(12)     | NO       |     | 用户手机号     |
| user_email    | varchar(255) | NO       |     | 用户邮箱       |
| user_pass     | char(50)     | NO       |     | 密码           |
| get_pass_time | datetime     |          |     | 重置密码的时间 |

#### 2.读者信息表（Reader）

| 名字       | 数据类型 | 是否为空 | 键  | 说明       |
| ---------- | -------- | -------- | --- | ---------- |
| user_ID    | int      | NO       | PRI | 用户编号   |
| user_name  | char(20) | NO       |     | 用户姓名   |
| user_sex   | char(4)  | NO       |     | 性别       |
| user_grade | char(10) | NO       |     | 年级       |
| user_Pro   | char(50) | NO       |     | 专业       |
| pun_time   | int      | NO       |     | 被处罚次数 |
| inter      | int      | NO       |     | 诚信度     |

#### 3.图书信息表（Book)

| 名字        | 数据类型     | 是否为空 | 键  | 说明     |
| ----------- | ------------ | -------- | --- | -------- |
| book_ID     | int          | NO       | PRI | 图书编号 |
| book_name   | varchar(255) | NO       |     | 书名     |
| book_author | varchar(50)  | NO       |     | 作者     |
| book_pub    | varchar(50)  | NO       |     | 出版社   |
| book_cate   | varchar(30)  | NO       |     | 分类     |
| book_date   | datetime     | NO       |     | 入库时间 |
| book_borrow | tinyint(1)   | NO       |     | 是否借出 |
| book_times  | int          | NO       |     | 借出次数 |

#### 4.图书借还信息表（Borrow）

| 名字      | 数据类型   | 是否为空 | 键  | 说明         |
| --------- | ---------- | -------- | --- | ------------ |
| user_ID   | int        | NO       | PRI | 用户编号     |
| book_ID   | int        | NO       | PRI | 图书编号     |
| br_date   | datetime   | NO       | PRI | 借书时间     |
| exp_date  | datetime   | NO       |     | 预期归还时间 |
| act_date  | datetime   |          |     | 实际归还时间 |
| over_date | int        | NO       |     | 超期天数     |
| penalty   | float      | NO       |     | 处罚金额     |
| clear     | tinyint(1) | NO       |     | 是否缴纳处罚 |

#### 5.管理员表（Manager）

| 名字      | 数据类型     | 是否为空 | 键  | 说明       |
| --------- | ------------ | -------- | --- | ---------- |
| mng_ID    | int          | NO       | PRI | 管理员编号 |
| mng_name  | char(20)     | NO       |     | 管理员姓名 |
| mng_email | varchar(255) | NO       |     | 管理员邮箱 |
| mng_phone | char(12)     | NO       |     | 管理员电话 |
| mng_pass  | char(50)     | NO       |     | 管理员密码 |

#### 6.公告表（Public）

| 名字        | 数据类型 | 是否为空 | 键  | 说明       |
| ----------- | -------- | -------- | --- | ---------- |
| pub_ID      | int      | NO       | PRI | 公告编号   |
| pub_content | text     | NO       |     | 公告内容   |
| pub_mng     | int      | NO       |     | 发布管理员 |
| pub_time    | datetime | NO       |     | 发布时间   |
