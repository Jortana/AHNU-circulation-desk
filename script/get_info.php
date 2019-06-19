<?php
    $fp = fopen("books.csv", "rb+");
    $tfp = fopen("books.sql", "ab");
    $number = 0;
    while($data = fgetcsv($fp, 1000, "#")) {
        if (count($data) < 7) {
            continue;
        }
        $sql = "insert into `bar_book` (`book_name`, `book_author`, `book_cate`, `book_pub`, `book_date`) values ";
        $name = $data[0];
        $cate = $data[1];
        $author = $data[4];
        $pubStr = $data[5];
        $pubArr = explode("#", $pubStr);
        $pub = $pubArr[0];
        $date = date("Y-m-d h:i:s");
        $sql .= '(' . '"' . $name . '", "' . $author . '", "' . $cate . '", "' . $pub . '", "' . $date . '"' . ");\n";
        if (fwrite($tfp, $sql)) {
            $number += 1;
            echo (string)$number . "本已写入books.sql\n";
        } else {
            echo "无法写入";
        }
    }
?>