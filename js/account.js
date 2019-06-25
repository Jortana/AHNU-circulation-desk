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
    } else {
        $('.info-container').removeClass('hide');
    }
    render_header();
    get_account_info();
    $('#ch-account').on('click', function() {
        if ($(this).hasClass('btn-primary')) {
            check_change();
        } else {
            display_change_form();
        }
    });

    function get_account_info() {
        $.ajax({
            type: "get",
            url: "../php/get_account_info.php",
            success: function (response) {
                response = JSON.parse(response);
                if (response.success != '1') {
                    alert(response.msg);
                }
                var info = response.account_info;
                $('#stu-number').text(info.number);
                $('#name').text(info.name);
                $('#sex').text(info.sex);
                $('#grade').text(info.grade);
                $('#pro').text(info.pro);
                $('#phone').text(info.phone);
                $('#email').text(info.email);
                $('#borrow-times').text(info.borrow_time);
                $('#over-times').text(info.pun);
                $('#inter').text(info.inter);
            }
        });
    }

    function display_change_form() {
        var $account = $('#account'),
            $ch_div = $('#ch-div'),
            $div_phone = $(`<div id="div-phone" class="div-info">
                                <h3>手机号码</h3>
                                <div class="info input-group-lg">
                                    <input type="text" id="ch-phone" class="form-control">
                                </div>
                                <div id="phone-repeat-error" class="error alert alert-danger" role="alert">
                                    <span class="sr-only">Error:</span>
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                     手机号码已存在
                                </div>
                            </div>`),
            $div_email = $(`<div id="div-email" class="div-info">
                                <h3>电子邮箱</h3>
                                <div class="info input-group-lg">
                                    <input type="email" id="ch-email" class="form-control">
                                </div>
                                <div id="email-repeat-error" class="error alert alert-danger" role="alert">
                                    <span class="sr-only">Error:</span>
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                     电子邮箱已存在
                                </div>
                            </div>`),
            org_phone = $('#phone').text(),
            org_email = $('#email').text();
        $account.children('.col-xs-4').children().hide();
        toggle_btn_account();
        $ch_div.append($div_phone);
        $('#ch-phone').val(org_phone);
        $ch_div.append($div_email);
        $('#ch-email').val(org_email);
    }

    function check_change() {
        var $account = $('#account'),
            org_phone = $('#phone').text(),
            org_email = $('#email').text(),
            new_phone = $('#ch-phone').val(),
            new_email = $('#ch-email').val(),
            $div_phone = $('#div-phone'),
            $div_email = $('#div-email');
        
        if (new_phone == org_phone && new_email == org_email) {
            $div_phone.remove();
            $div_email.remove();
            $account.children('.col-xs-4').children().show();
            toggle_btn_account();
        } else {
            go_check(new_phone, new_email);
        }
    }

    function go_check(new_phone, new_email) {
        $.ajax({
            type: "post",
            url: "../php/check_change.php",
            data: {
                'new_phone': new_phone,
                'new_email': new_email,
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == '1') {
                    alert(response.msg);
                    window.location.reload();
                } else if (response.code == '2') {
                    $('#phone-repeat-error').show();
                    $('#ch-phone').trigger('select');
                } else if (response.code == '3') {
                    $('#email-repeat-error').show();
                    $('#ch-email').trigger('select');
                } else {
                    $('#phone-repeat-error').show();
                    $('#email-repeat-error').show();
                    $('#ch-phone').trigger('select');
                }
            }
        });
    }

    function toggle_btn_account() {
        var $btn_account = $('#ch-account');
        if ($btn_account.hasClass('btn-primary')) {
            $btn_account.removeClass('btn-primary');
            $btn_account.text('编辑');
        } else {
            $btn_account.addClass('btn-primary');
            $btn_account.text('完成');
        }
        $btn_account.trigger('blur');
    }  
})