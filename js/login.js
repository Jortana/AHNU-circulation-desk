$(function () {
    'use strict';

    var $form = $('#log-form');
    var $input = $('#loginID');
    
    function name(params) {
        $form.on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                method: "post",
                url: "url",
                data: {
                    $input.attr('name'): $input.val();
                },
                success: function (response) {
                    
                }
            });
        })
    }
})