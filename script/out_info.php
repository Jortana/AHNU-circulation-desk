<?php
    $number  = 0;
    $fp = fopen("doubanbook/file/bookinfo.sql", "rb+");
    $ftp = fopen("bookinfo.sql", "wb+");
    while ($data = fgetcsv($fp)) {
        $sql = $data[0].",".$data[3].",".$data[4].",".$data[2].",".$data[8].","."'".date("Y-m-d h:i:s")."');\n";

        fwrite($ftp, $sql);
        $number += 1;
        echo "$number 条已插入\n";
    }
?>