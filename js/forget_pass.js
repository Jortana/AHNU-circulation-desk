$(function () {
    'use strict';

    var info = is_login();
    if (info !== 0) {
        alert("你都登录了还忘记了密码？？？");
        window.location.href="../index.html";
    } else {
        // 判定为未登录再显示登录界面
        $('body').removeClass('hide');
    }
    listen_change();
    $('#submit').on('click', function(e) {
        e.preventDefault();
        $(this).attr('disabled', 'disabled');
        var email = $('#email').val();
        $.ajax({
            type: "post",
            url: "../php/search_email.php",
            data: {
                'email': email,
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.code == '-2') {
                    $('#email-not-exist-error').show();
                }
                
                if (response.success == '1') {
                    $('#email').attr('disabled', 'disabled');
                    $('#success').removeClass('hide');
                } else if (response.success == '-12') {
                    $('#not-send').show();
                }
            }
        });
    });

    function listen_change() {
        var $btn_submit = $('#submit');
        $('#email').on('input', function() {
            if ($(this).val() !== '') {
                $btn_submit.removeAttr('disabled');
            } else {
                $btn_submit.attr('disabled', 'disabled');
            }
    
            $('#email-not-exist-error').hide();
        })
    }
})