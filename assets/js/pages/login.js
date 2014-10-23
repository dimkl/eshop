$(document).ready(function () {
    var loginBtn = $('#login-btn');
    var showPasswordBtn = $('#showPassword-btn');
    var form = $('#login-form');
    var statusLabel = $('#statusLabel');
    var statusLabelClass = {
        'error': 'col-xs-12 label label-danger',
        'success': 'col-xs-12 label label-success'
    };
    /**
     * Click event handler for login button
     */
    $(loginBtn).click(function () {

        $(form).ajaxFormSubmit({type: "POST", url: "ajax/account/login"},
        function success(response) {
            try {
                response = JSON.parse(response);
            } catch (e) {
                throw "Invalid response. Response must be json";
            }
            if (response.status === "success") {
                $(statusLabel).html(response.data.message);
                $(statusLabel).attr('class', statusLabelClass.success);
                setTimeout(function () {
                    location.href = "navigation/product/preview/1";
                }, 3000);
            } else {
                $(statusLabel).html(response.errormessage);
                $(statusLabel).attr('class', statusLabelClass.error);
            }
        }, function error(response) {
            $(statusLabel).html("An error occured during connecting to server. Please refresh and try again");
        });
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
    /***************************************************/
    /*                Initiation                       */
    /***************************************************/
    //validation 
    $(form).validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            }
        }
    });
});