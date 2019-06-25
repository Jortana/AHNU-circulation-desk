$(function () {
    'use strict';

    var info = is_login();
    if (info == 0) {
        var $li = $('<li class="li_book_info"></li>'),
            $panel = $('<div class="panel panel-danger"></div>'),
            $pheading = $('<div class="panel-heading">您还未登录</div>'),
            $pbody = $('<div class="panel-body">请先登录</div>');

        $panel.append($pheading);
        $panel.append($pbody);
        $li.append($panel);
        $('#ul-results').append($li);
    } else {
        var info_arr = info.split("|"),
            name = info_arr[0],
            $header = $('#header');
        
        // 渲染一个header
        $header.append("<adminheader></adminheader>");
        new Vue({
            el: '#header',
        });
        var li_info = $('#info'),
            a_name = '<a href="#" style="color: #fff">' + name + '</a>';
        li_info.prepend(a_name);
        listen();
        set_active("public");
        add_url_info('p');
    }
    
})