// 存在不同js中使用超过一次的函数

// 设置.active
function set_active(name) {
    var selector = '.navbar #goto-' + name;
    $(selector).addClass('active');
}

// 根据是否登录渲染header
function render_header() {
    // 判断是否登录
    var info = is_login();

    if (info !== 0) {
        // 已登录
        var info_arr = info.split("|");
        var name = info_arr[0],
            id = info_arr[1],
            $header = $('#header');

        // 渲染一个header
        $header.append("<cmheader></cmheader>");
        new Vue({
            el: '#header',
        });
        // 隐藏未登录的样式,显示已登录的样式
        var nav_no_login = $('#nav-right-nologin'),
            nav_login = $('#nav-right-login'),
            li_info = $('#info'),
            // 没有找到其他设置这个颜色的方法,均会被覆盖掉,原因不明,只能行内写style
            a_name = '<a href="#" style="color: #fff">' + name + '</a>';
        nav_no_login.hide();
        li_info.prepend(a_name);
        nav_login.removeClass("hide");
        listen();
    } else {
        var $header = $('#header');
        // 渲染一个header
        $header.append("<cmheader></cmheader>");
        new Vue({
            el: '#header',
        });
    }
}

// 判断是否登录,若已登录返回info,否则返回0
function is_login() {
    var info;

    $.ajax({
        type: "GET",
        url: "../php/is_login.php",
        // 这里一定要加下面这一句,不然无返回值,又因为是初始化页面的函数,所以同步也不是很影响用户体验
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

// 添加网页信息,用于登录后的跳转页
function add_url_info(info) {
    var $btn_login = $('#goto-login a'),
        $attr = $btn_login.attr('href'),
        org_search = window.location.search;
    // 如果之前有信息,就在后面加f
    if (org_search.indexOf("?") != -1) {   
        $attr += org_search +'&f=' + info;
    } else {
        $attr += '?f=' + info;
    }
    $btn_login.attr('href', $attr);
}

// 获取url中的参数
function get_query_string(name) {
    //正则表达式,获取地址中的参数
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);

    if (r) {
        return r[2].replace(/\+/g, " ");
        // return unescape(r[2]);
    } else {
        return null;
    }
}

// header下拉栏的监听事件
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
        url: "../php/logout.php",
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

// 设置搜索下拉栏的监听事件
function listen_select() {
    // 搜索下拉菜单的设置
    var $select_menu = $('.select-menu');

    // 选出搜索分类的条目
    $select_menu.each(function (index, node) {
        $(this).on('click', function () {
            var $attr = $(this).attr('id'),
                $attr_arr = $attr.split('-');
            $('#search-type').text($(this).text());
            $('#hidden-type').attr('value', $attr_arr[1]);
        });
    });
}

// 在搜索空字符串时,阻止submit
function prevent_null() {
    $('#search-submit').on('click', function(e) {
        var $query = $('#query'),
            query = $query.val().trim();
        if (query == '') {
            e.preventDefault();
        }
    });
}
