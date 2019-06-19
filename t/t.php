<?php
    // goodreads API
    // key: YRrktgLVape4u8jesXybnQ
    // secret: CbSyl4Cz4loIjtN8uyD9sdOXEeDFaW0fV8216ZBVnB4
    // curl "https://www.goodreads.com/search.xml?key=YRrktgLVape4u8jesXybnQ&q=Ender%27s+Game"

    // 思路：
    // 1. 用聚合数据的API获取很多书名，存入文件
    // 2. 用goodreads的API查询书名，将查询到的全部内容存入数据库
?>