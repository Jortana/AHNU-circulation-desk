<?php
    require("../lib/db_fns.php");

    $response = [
        'success' => '0',
        // 默认-1代表未连接数据库
        'code' => '-1',
        'msg' => '连接数据库失败，请联系管理员或稍后再试',
        'count' => 0,
        'data' => [],
    ];
    $type = $_POST['type'];
    $q = $_POST['q'];
    $offset = $_POST['offset'];
    if ($offset == '' || (int)$offset < 0) {
        $offset  = '0';
    }

    try {
        $conn = db_connect();
    } catch (Exception $e) {
        echo json_encode($response);
        exit();
    }

    $query = "select count(distinct book_name) from bar_book where book_".$type." like '%$q%';";
    $number = $conn->query($query);
    $number = $number->fetch_row();
    if ((int)$number[0] > 0) {
        $response['success'] = '1';
        $response['code'] = 0;
        $response['msg'] = '查询成功';
        $response['number'] = $number;
    } else {
        $response['success'] = '1';
        $response['code'] = '4';
        $response['msg'] = '查询成功，无匹配数据';
        $response['number'] = '0';

        echo json_encode($response);
        exit();
    }

    // 现在开始查询，返回10条结果
    $info_query = "select distinct book_name, book_author, book_cate, book_pub from bar_book where book_".$type." like '%$q%' limit 10 offset $offset;";
    $result = $conn->query($info_query);
    while ($row = $result->fetch_assoc()) {
        // 先写入图书基本信息
        $data_item = [];
        $data_item['name'] = $row['book_name'];
        $data_item['author'] = $row['book_author'];
        $data_item['cate'] = $row['book_cate'];
        $data_item['pub'] = $row['book_pub'];

        // 对于每个结果还需要查询复本数和在馆数
        // 这一步应该和图书基本信息分离做成异步
        // 但考虑到数据量并不大,若查询返回速度较慢,这里是一个可优化的地方
        $number_query = "select book_ID, book_borrow from bar_book
                        where book_name = "."'".$row['book_name']."'".
                        " and book_author = "."'".$row['book_author']."'".
                        " and book_cate = "."'".$row['book_cate']."'".
                        " and book_pub = "."'".$row['book_pub']."';";
        $number_result = $conn->query($number_query);
        $data_item['total'] = $number_result->num_rows;
        $in_library = 0;
        $ID = '';
        while($sg_book = $number_result->fetch_assoc()) {
            if ($sg_book['book_borrow'] == 0) {
                $ID .= $sg_book['book_ID'].'|';
                $in_library += 1;
            }
        }
        $data_item['book_ID'] = $ID;
        $data_item['in_library'] = $in_library;
        
        array_push($response['data'], $data_item);
    }

    echo json_encode($response);
?>