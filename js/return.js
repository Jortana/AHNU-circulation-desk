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
    render_header();
    set_active("return");
    add_url_info('r');
})