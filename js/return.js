$(function () {
    'use strict';
    
    if (is_login() == 0) {
        var $li = $('<li class="li_book_info"></li>'),
            $panel = $('<div class="panel panel-danger"></div>'),
            $pheading = $('<div class="panel-heading">您还未登录</div>'),
            $pbody = $('<div class="panel-body">请先登录</div>');

        $panel.append($pheading);
        $panel.append($pbody);
        $li.append($panel);
        $('#ul-results').append($li);
    }
    prevent_admin();
    render_header();
    set_active("return");
    add_url_info('r');
    search_need_return();

    function search_need_return() {
        $.ajax({
            type: "get",
            url: "../php/search_need_return.php",
            success: function (response) {
                response = JSON.parse(response);
                if (response.code == '-10') {
                    alert(response.msg);
                    window.location.href = '../index.html';
                }
                display_return_info(response);
            }
        });
    }

    function display_return_info(response) {
        if (response.data.length == 0) {
            // 无借用
            var $li = $('<li class="li_book_info"></li>'),
                $panel = $('<div class="panel panel-info"></div>'),
                $pheading = $('<div class="panel-heading">目前没有需要归还的图书</div>'),
                $pbody = $('<div class="panel-body">去借点书看吧</div>');

            $panel.append($pheading);
            $panel.append($pbody);
            $li.append($panel);
            $('#ul-results').append($li);
        } else {
            $.each(response.data, function () {
                var $li = $('<li class="li_book_info"></li>'),
                    $panel = $('<div class="panel"></div>'),
                    $pheading = $('<div class="panel-heading"></div>'),
                    $pbody = $('<div class="panel-body row"></div>'),
                    $pfooter = $('<div class="panel-footer row"></div>'),
                    $author = '<div class="col-xs-4">作者：' + this.author + '</div>',
                    $cate = '<div class="col-xs-4">分类：' + this.cate + '</div>',
                    $pub = '<div class="col-xs-4">出版社：' + this.pub + '</div>',
                    $br_date = '<div class="col-xs-4">借阅时间：' + this.br_date.substr(0, 10) +'</div>',
                    $exp_date = '<div class="col-xs-4">最晚归还：' + this.exp_date.substr(0, 10) + '</div>',
                    $penalty = '<div class="col-xs-4">超期处罚金额：' + this.penalty + '</div>',
                    $btn_return = $('<button type="button" class="btn btn-primary btn-block col-xs-4 btn-return">归还</button>'),
                    $btn_pay = $('<button type="button" class="btn btn-danger btn-block col-xs-4 btn-pay">缴费并归还</button>');
                if (this.penalty > 0) {
                    var $btn_insert = $btn_pay;
                    $panel.addClass('panel-danger');
                } else {
                    var $btn_insert = $btn_return;
                    $panel.addClass('panel-info');
                }

                var book_ID = this.book_ID;
                $btn_insert.attr('data-ID', book_ID);

                // 给按钮绑定事件
                $btn_return.on('click', function() {
                    go_return(book_ID);
                })
                $btn_pay.on('click', function() {
                    go_pay_return(book_ID);
                })

                $pheading.text(this.name);
                $pbody.append($author + $cate + $pub);
                $pfooter.append($br_date + $exp_date + $penalty);
                $panel.append($pheading);
                $panel.append($pbody);
                $panel.append($pfooter);
                $panel.append($btn_insert);
                $li.append($panel);
                $('#ul-results').append($li);
            });            
        }
    }

    function go_return(book_ID) {
        $.ajax({
            type: "post",
            url: "../php/return.php",
            data: {
                'book_ID': book_ID,
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == 1) {
                    alert('还书成功');
                    window.location.reload();
                } else {
                    alert(response.msg);
                    window.location.reload();
                }
            }
        });
    }

    function go_pay_return(book_ID) {
        alert('假装自己付了款');
        $.ajax({
            type: "post",
            url: "../php/pay_return.php",
            data: {
                'book_ID': book_ID,
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == 1) {
                    alert('还书成功');
                    window.location.reload();
                } else {
                    alert(response.msg);
                    window.location.reload();
                }
            }
        });
    }
})