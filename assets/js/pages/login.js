
$(document).ready(function () {
    /**
     * Serialize form in json format
     * 
     * @returns {Array|RegExp.fn.serializeObject.result}
     */
    $.fn.serializeObject = function () {
        "use strict";
        var result = {};
        var extend = function (i, element) {
            var node = result[element.name];

            // If node with same name exists already, need to convert it to an array as it
            // is a multi-value field (i.e., checkboxes)

            if ('undefined' !== typeof node && node !== null) {
                if ($.isArray(node)) {
                    node.push(element.value);
                } else {
                    result[element.name] = [node, element.value];
                }
            } else {
                result[element.name] = element.value;
            }
        };

        $.each(this.serializeArray(), extend);
        return result;
    };
    /**
     * Submit form with ajax method
     * 
     * @returns void
     */
    $.fn.ajaxFormSubmit = function () {
        if (!$(this).is('form')) {
            throw new Exception('Element is not a form.');
        }
        var serializedData = $(this).serializeObject();
        var stringifiedData = "";
        //check if serializedData is json
        try {
            stringifiedData = JSON.stringify(serializedData);
        } catch (e) {
            console.log(e);
            throw new Exception('Element is not a form.');
        }
        $.ajax({type: "POST", url: "/ajax/user/create", data: stringifiedData})
                .done(function (response) {
                    console.log('done');
                    console.log(response)
                })
                .fail(function (response) {
                    console.log('fail', arguments);
                    console.log(response)
                });
    };

    var loginBtn = $('#btn-login');
    var showPasswordBtn = $('#btn-showPassword');
    /**
     * Click event handler for login button
     */
    $(loginBtn).click(function () {
        $('#login-form').ajaxFormSubmit();
    });
    /**
     * Click event handler for show password checkBox
     */
    $(showPasswordBtn).click(function () {
        var key_attr = $('#key').attr('type');

        if (key_attr != 'text') {

            $('.checkbox').addClass('show');
            $('#key').attr('type', 'text');

        } else {

            $('.checkbox').removeClass('show');
            $('#key').attr('type', 'password');

        }

    });
});