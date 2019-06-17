$(function () {
    'use sctrict';

    var $inputs = $('.form-control'),
        inputs = [],
        $form = $('form');

    // 设置.active
    $('.navbar #goto-register').addClass('active');

    // 获取页面中的input
    $inputs.each(function (index, node) {
        var tmp = new Input(node);
        inputs.push(tmp);
    })

    $form.on('submit', function (e) {
        e.preventDefault();
        // 检查是否有空项,顺便重置重复项提醒
        for (var i = 0; i < inputs.length; i++) {
            var r = inputs[i].is_empty();
            inputs[i].hide_repeat();
            if (r) {
                var any_empty = true;
            }
        }
        if (any_empty) {
            return;
        }

        go_ajax();
    })

    function go_ajax() {
        var data = {};

        $inputs.each(function (index, node) {
            data[node.name] = node.value;
        })

        $.ajax({
            type: "POST",
            url: "../php/register.php",
            data: data,
            success: function (result) {
                result = eval('(' + result + ')');
                var code_arr = result.code.split("|"),
                    msg_arr = result.msg.split("|");
                
                // 测试用的console
                // console.log(code_arr);
                // console.log(msg_arr);

                for (var i=0; i<code_arr.length; i++) {
                    switch(code_arr[i]) {
                        case '0':
                            alert('注册成功');
                            window.location('../account/login.html')
                            break;
                        case '1':
                            $('#stu-number-repeat-error').show();
                            break;
                        case '2':
                            $('#phone-repeat-error').show();
                            break;
                        case '3':
                            $('#email-repeat-error').show();
                            break;
                    }
                }
            },
        });
    }
})