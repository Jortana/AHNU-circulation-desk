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
    search_history();

    function search_history() {
        $.ajax({
            type: "get",
            url: "../php/search_history.php",
            success: function (response) {
                response = JSON.parse(response);
                if (response.success != '1') {
                    alert(response.msg);
                    window.location.href = "../index.html";
                } else if(response.count == 0){
                    display_no_history();
                } else {
                    display_history(response);
                }
            }
        });
    }

    function display_no_history() {
        var $li = $('<li class="li_book_info"></li>'),
            $panel = $('<div class="panel panel-info"></div>'),
            $pheading = $('<div class="panel-heading">你还没有借过书</div>'),
            $pbody = $('<div class="panel-body">去借点书看吧</div>');

        $panel.append($pheading);
        $panel.append($pbody);
        $li.append($panel);
        $('#ul-results').append($li);
    }

    function display_history(response) {
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
                $exp_date = '<div class="col-xs-4">归还时间：' + this.act_date.substr(0, 10) + '</div>',
                $penalty = '<div class="col-xs-4">超期处罚金额：' + this.penalty + '</div>';
            
            // 如果有penalty,加一个.dander
            if (this.penalty > 0) {
                $panel.addClass('panel-danger');
            } else {
                $panel.addClass('panel-info');
            }
            
            $pheading.text(this.name);
            $pbody.append($author + $cate + $pub);
            $pfooter.append($br_date + $exp_date + $penalty);
            $panel.append($pheading);
            $panel.append($pbody);
            $panel.append($pfooter);
            $li.append($panel);
            $('#ul-results').append($li);
        });
    }
})