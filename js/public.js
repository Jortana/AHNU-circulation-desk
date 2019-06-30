$(function () {
    'use strict';

    check_admin();
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
    }
    render_header();
    $('#public-form').on('submit', function(e) {
        e.preventDefault();
        go_public();
    });
    
    function go_public() {
        var content = $('#public').val();
        $.ajax({
            type: "post",
            url: "../php/public.php",
            data: {
                'content': content,
            },
            success: function (response) {
                response = JSON.parse(response);
                alert(response.msg);
                window.location.reload();
            }
        });
    }
})