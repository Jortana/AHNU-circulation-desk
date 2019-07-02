$(function () {
    'use strict';
    $('#verify').on('click', function(e) {
        e.preventDefault();
        var data = grecaptcha.getResponse();
        $.ajax({
            type: "post",
            url: "test.php",
            data: {
                'data': data,
            },
            success: function (response) {
                console.log(response);
            }
        });
    })
})