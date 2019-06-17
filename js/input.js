$(function () {
    'use sctrict';

    window.Input = function (selector) {
        
        var $ele,
            $empty_error,
            $repeat_error,
            $rule_error_ele,
            rule = {},
            me = this;

        function init() {
            get_ele();
            get_empty_error();
            get_repeat_error();
            get_rule_error_ele();
            parse_rule();
            me.load_validator();
            listen();
        }

        this.get_val = function () {
            return $ele.val();
        }

        this.is_empty = function () {
            var val = this.get_val();
            if (val == '') {
                $($empty_error).show();
                return true;
            } else {
                $($empty_error).hide();
                return false;
            }
        }

        this.hide_repeat = function () {
            $($repeat_error).hide();
        }

        this.load_validator = function () {
            var val = this.get_val();
            this.validator = new Validator(val, rule);
        }

        function get_ele() {
            if (selector instanceof jQuery) {
                $ele = selector;
            } else {
                $ele = $(selector);
            }
        }

        function get_empty_error() {
            $empty_error = '#' + $ele.attr('name') + '-empty-error';
        }

        function get_repeat_error() {
            $_repeat_error = '#' + $ele.attr('name') + '-repeat-error';
        }

        function get_rule_error_ele() {
            var rule_error = '#' + $ele.attr('name') + '-rule-error';
            $rule_error_ele = $(rule_error);
        }

        function parse_rule() {
            var i;
            var rule_str = $ele.data('rule');
            if (!rule_str) {
                return;
            }
            
            var rule_arr = rule_str.split('|'); // [ 'min:18', 'maxlength:10']
            for (i = 0; i < rule_arr.length; i++) {
                var item_str = rule_arr[i];
                var item_arr = item_str.split(':'); // ['min','18']
                rule[item_arr[0]] = JSON.parse(item_arr[1]); // {min: 18}
            }
        }

        function listen() {
            $ele.on('blur', function () {
                me.is_empty();
                var valid = me.validator.validate_min(me.get_val());
                if (valid)
                    $rule_error_ele.hide();
                else
                    $rule_error_ele.show();
            })
        }

        init();
    }
})