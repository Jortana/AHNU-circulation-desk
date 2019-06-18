$(function () {
    'use strict';

    // 判断是否登录
    var info = is_login();

    if (info !== 0) {
        // 已登录
        var info_arr = info.split("|");
        var name = info_arr[0],
            id = info_arr[1],
            $header = $('#header');

        // 渲染一个header
        $header.append("<indexheader></indexheader>");
        new Vue({
            el: '#header',
        });
        // 隐藏未登录的样式，显示已登录的样式
        var nav_no_login = $('#nav-right-nologin'),
            nav_login = $('#nav-right-login'),
            li_info = $('#info'),
            // 没有找到其他设置这个颜色的方法，均会被覆盖掉，原因不明，只能行内写style
            a_name = '<a href="#" style="color: #fff">' + name + '</a>';
        nav_no_login.hide();
        li_info.prepend(a_name);
        nav_login.removeClass("hide");
        listen();
    } else {
        var $header = $('#header');
        // 渲染一个header
        $header.append("<indexheader></indexheader>");
        new Vue({
            el: '#header',
        });
    }
    // 设置.active
    $('.navbar #goto-index').addClass('active');

    // 搜索下拉菜单的设置
    var search_type = $('#search_type'),
        $select_menu = $('.select-menu');

    // 选出搜索分类的条目
    $select_menu.each(function (index, node) {
        $(this).on('click', function () {
            $('#search-type').text($(this).text());
            $('#hidden-type').attr('value', $(this).text());
        });
    });

    // 这是一条重复的函数，但是似乎不能存在其他文件中然后引用，因为index也用了
    // 这样的目录结构无法确定url
    function is_login() {
        var info;
    
        $.ajax({
            type: "GET",
            url: "php/is_login.php",
            // 这里一定要加下面这一句，不然无返回值，又因为是初始化页面的函数，所以同步也不是很影响用户体验
            async : false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.logged === true) {
                    info = response.info;
                } else {
                    info = 0;
                }
            }
        });
        
        return info;
    }

    function listen() {
        // 开启下拉栏
        open_drop_down();
        listen_logout();
    }

    function open_drop_down() {
        var $account = $('#nav-right-login');
        var $account_menu = $('#account-menu');
        $account.on('mouseenter', function () {
            $(this).addClass('open');
        });
        $account_menu.on('mouseleave', function () { 
            $('#nav-right-login').removeClass('open');
        });
    }

    function listen_logout() {
        $('#logout').on('click', function() {
            go_logout(); 
        });
    }

    function go_logout() {
        $.ajax({
            type: "get",
            url: "php/logout.php",
            success: function (response) {
                if (response == 1) {
                    alert("退出成功");
                    window.location.reload();
                } else {
                    alert("您还未登录");
                }
            }
        });
    }
})