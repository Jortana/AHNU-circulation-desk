$(function () {
    'use sctrict';

    $('.nav-center').hide();
    render_header();
    set_active("borrow");
    add_url_info('b');
    listen_select();
    var type = get_query_string('type'),
        q = get_query_string('q'),
        offset = get_query_string('offset'),
        data;
    q = decodeURI(q);
    set_input(type, q);
    prevent_null();

    if ((type != null) && (q != null)) {
        data = go_search(type, q, offset);
        $('.nav-center').show();
    }
    
    // 下面是函数们
    function set_input(type, q) {
        if (type !== null && q !== null){
            // 根据用户的输入设置搜索框
            var select_id = '#select-' + type;
            $(select_id).trigger('click');
            $('#query').val(q);
        }  
    }

    function go_search(type, q, offset) {
        data = {
            'type': type,
            'q': q,
            'offset': offset,
        };
        $.ajax({
            type: "post",
            url: "../php/search.php",
            data: data,
            success: function (response) {
                response = JSON.parse(response);
                if (response.number <= 0) {
                    var $li = $('<li class="li_book_info"></li>'),
                        $panel = $('<div class="panel panel-danger"></div>'),
                        $pheading = $('<div class="panel-heading">未找到匹配的内容</div>'),
                        $pbody = $('<div class="panel-body">请联系管理员订购图书</div>');

                    $panel.append($pheading);
                    $panel.append($pbody);
                    $li.append($panel);
                    $('#ul-results').append($li);
                }
                $.each(response.data, function () {
                    // 显示10条结果
                    var $li = $('<li class="li_book_info"></li>')
                        $panel = $('<div class="panel panel-info"></div>'),
                        $pheading = $('<div class="panel-heading"></div>'),
                        $pbody = $('<div class="panel-body row"></div>'),
                        $pfooter = $('<div class="panel-footer row"></div>'),
                        $div = $(''),
                        $author = '<div class="col-xs-4">作者：' + this.author + '</div>',
                        $cate = '<div class="col-xs-4">分类：' + this.cate + '</div>',
                        $pub = '<div class="col-xs-4">出版社：' + this.pub + '</div>',
                        $book_number = '<div class="col-xs-4">复本数：' + this.total +'</div>',
                        $book_last = '<div class="col-xs-4">剩余数：' + this.in_library + '</div>',
                        $btn_borrow = $('<button type="button" class="btn btn-primary col-xs-4 btn-borrow">借阅</button>');

                    // 设置按钮的样式和储存数据
                    // 没有库存就显示淡色按钮
                    if (this.in_library == 0) {
                        $btn_borrow.addClass('disabled');
                    }
                    $btn_borrow.attr('data-ID', this.book_ID);

                    $pheading.text(this.name);
                    $pbody.append($author + $cate + $pub);
                    $pfooter.append($book_number + $book_last);
                    $pfooter.append($btn_borrow);
                    $panel.append($pheading);
                    $panel.append($pbody);
                    $panel.append($pfooter);
                    $li.append($panel);
                    $('#ul-results').append($li);
                });

                // 设置翻页导航
                set_page_nav(response);
                // 借阅按钮添加借阅事件
                btn_bind_borrow();
            }
        });
    }

    function set_page_nav(response) {
        // 显示翻页导航栏
        var number = Number(response.number),
        $pre_page = $('#pre-page'),
        $next_page = $('#next-page');

        if (number <= 10) {
            var $btn_page = $('<li class="active"><a href="#">1</a></li>');
            
            $next_page.before($btn_page);
            $pre_page.children('a').replaceWith(`
                <span>
                    <span aria-hidden="true">&laquo;</span>
                </span>`);
            $pre_page.addClass('disabled');
            $next_page.children('a').replaceWith(`
                <span>
                    <span aria-hidden="true">&raquo;</span>
                </span>`);
            $next_page.addClass('disabled');

        } else {
            if (offset == null) {
                offset = 0;
            }
            offset = Number(offset);
            pages = number / 10;
            pages = Math.ceil(pages);
            // 设置前一页和后一页的href
            if (offset == 0) {
                $pre_page.children('a').replaceWith(`
                    <span>
                        <span aria-hidden="true">&laquo;</span>
                    </span>`);
                $pre_page.addClass('disabled');
            } else {
                var pre_offset = offset - 10,
                    pre_href = '../b_and_r/borrow.html?type=' + type + '&q=' + q + '&offset=' + pre_offset;
                $pre_page.children('a').attr('href', pre_href);
            }

            if ((offset+10)  / 10 >= pages)
            {
                $next_page.children('a').replaceWith(`
                    <span>
                        <span aria-hidden="true">&raquo;</span>
                    </span>`);
                $next_page.addClass('disabled');
            } else {
                var next_offset = offset + 10,
                    next_href = '../b_and_r/borrow.html?type=' + type + '&q=' + q + '&offset=' + next_offset;
                $next_page.children('a').attr('href', next_href);
            }
            
            if (pages > 7) {
                // 浏览前5页是一个样式
                if (offset < 50) {
                    for (var i=1; i<=7; i++) {
                        offset = (i-1) * 10;
                        var href = '../b_and_r/borrow.html?type=' + type + '&q=' + q + '&offset=' + offset,
                            $btn_page = $('<li><a href=""></a></li>');
                        $btn_page.children('a').text(i);
                        $btn_page.children('a').attr('href', href);
                        $next_page.before($btn_page);
                    }
                } else {
                        // 第一页的按钮
                    var $btn_page = $('<li><a href="">1</a></li>'),
                        // 省略号的按钮
                        $dots = $('<li><span>...</span></li>'),
                        first_href = '../b_and_r/borrow.html?type=' + type + '&q=' + q + '&offset=0',
                        start = Math.floor((offset+10) / 10) - 2;
                    // 设置1的href，并插入1和...
                    $btn_page.children('a').attr('href', first_href);
                    $next_page.before($btn_page);
                    $next_page.before($dots);

                    // 从当前页的前面两页开始显示
                    for (var i=1; i<=5; i++) {
                        offset = (start + i - 2) * 10;
                        // 判断是否到最大页数了
                        if (offset > pages * 10) {
                            $next_page.addClass('disabled');
                            $next_page.children('a').replaceWith(`
                                <span>
                                    <span aria-hidden="true">&raquo;</span>
                                </span>`);
                            break;
                        }
                        
                        var href = '../b_and_r/borrow.html?type=' + type + '&q=' + q + '&offset=' + offset,
                            $btn_page = $('<li><a href=""></a></li>');
                        $btn_page.children('a').text(start + i - 1);
                        $btn_page.children('a').attr('href', href);
                        $next_page.before($btn_page);
                    }
                }
            } else {
                // 页面数少于7
                for (var i=1; i<=pages; i++) {
                    offset = (i-1) * 10;
                    var href = '../b_and_r/borrow.html?type=' + type + '&q=' + q + '&offset=' + offset,
                        $btn_page = $('<li><a href=""></a></li>');
                    $btn_page.children('a').text(i);
                    $btn_page.children('a').attr('href', href);
                    $next_page.before($btn_page);
                }
            }
        }
    }

    // 借阅按钮添加借阅事件
    function btn_bind_borrow() {
        $('.btn-borrow').on('click', function() {
            if ($(this).hasClass('disabled')) {
                return;
            }
            info = is_login();
            if(info == 0) {
                href = $('#goto-login a').attr('href');
                alert('您还未登录，请先登录');
                window.location.href = href;
            } else {
                go_borrow(this);
            }
        });
    }

    // 借阅
    function go_borrow(button) {
        var data = {},
            IDs = $(button).attr('data-ID'),
            ID_arr = IDs.split('|');
            prevs = $(button).prevAll();

        if (ID_arr != "") {
            data['ID'] = ID_arr[0];
        }

        borrow_ajax(data);
    }

    function borrow_ajax(data) {
        $.ajax({
            type: "post",
            url: "../php/borrow.php",
            data: data,
            success: function (response) {
                // response = JSON.parse(response);
                console.log(response);
                if (response.code == 0) {
                    alert("借阅成功");
                    window.location.reload();
                } else {
                    alert(response.msg);
                    window.location.reload();
                }
            }
        });
    }
})