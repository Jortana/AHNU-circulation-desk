$(function () {
    'use strict';

    var email = get_query_string('email'),
        token = get_query_string('token');
    
    check_validation();
    listen_change();
    listen_submit();

    function check_validation() {
        $.ajax({
            type: "post",
            url: "../php/reset_validate.php",
            data: {
                'email': email,
                'token': token,
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == 0) {
                    alert(response.msg);
                    window.location.href = '../index.html';
                } else {
                    $('body').removeClass('hide');
                }
            }
        });
    }

    function listen_change() {
        var $btn_submit = $('#submit'),
            $new = $('#new-pass-word');
        $new.on('input', function() {
            if ($(this).val() !== '') {
                $btn_submit.removeAttr('disabled');
            } else {
                $btn_submit.attr('disabled', 'disabled');
            }
        })
    }

    function listen_submit() {
        var $btn_submit = $('#submit'),
            $new = $('#new-pass-word');

        $btn_submit.on('click', function(e) {
            e.preventDefault();
            var new_password = $new.val();
            $.ajax({
                type: "post",
                url: "../php/reset_pass_word.php",
                data: {
                    'email': email,
                    'new_password': new_password,
                },
                success: function (response) {
                    response = JSON.parse(response);
                    alert(response.msg);
                    if (response.success == 1) {
                        window.location.href = "../index.html";
                    }
                }
            });
        })
    }
})