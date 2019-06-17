$(function () {
    'use strict';

    // 设置.active
    $('.navbar #goto-login').addClass('active');
    
    // 选中三个input
    var $ipt_id = $('#login-id'),
        $ipt_pass = $('#password'),
        $ipt_submit = $('#submit');
    
    $ipt_id.on('input', function() {
        show_and_hide();
    })

    $ipt_pass.on('input', function() {
        show_and_hide();
    })

    $ipt_submit.on('click', function(e) {
        e.preventDefault();
        go_ajax();
    })

    function show_and_hide() {
        if ($ipt_id.val() !== '' && $ipt_pass.val() !== '') {
            $ipt_submit.removeAttr('disabled');
        } else {
            $ipt_submit.attr('disabled', 'disabled');
        }

        $('#id-not-exist-error').hide();
        $('#id-or-pass-error').hide();
    }

    function go_ajax() {
        var data = {};

        data['login-id'] = $ipt_id.val();
        data['password'] = $ipt_pass.val();
        console.log(data);

        $.ajax({
            type: "POST",
            url: "../php/login.php",
            data: data,
            success: function (response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.success == "1") {
                    alert("登录成功");
                    window.location.href = '../index.html';
                } else if (response.code == '-1') {
                    alert(response.msg)
                } else if (response.code == '-2') {
                    // 用户名不存在
                    $ipt_pass.val("");
                    $ipt_id.select();
                    $('#id-not-exist-error').show();
                } else if (response.code == '-3') {
                    // 用户名或密码错误
                    $ipt_pass.select();
                    $('#id-or-pass-error').show();
                }
            },
        });
    }
})