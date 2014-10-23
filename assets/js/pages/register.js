$(document).ready(function () {
    var registerBtn = $('#register-btn');
    var form = $('#register-form');
    var statusLabel = $('#statusLabel');
    var statusLabelClass = {
        'error': 'col-xs-12 label label-danger',
        'success': 'col-xs-12 label label-success'
    };
    /**
     * Click event handler for login button
     */
    $(registerBtn).click(function () {

        $(form).ajaxFormSubmit({type: "POST", url: "ajax/account/register"},
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
                    location.href = "navigation/account/login";
                }, 3000);
            } else {
                $(statusLabel).html(response.errormessage);
                $(statusLabel).attr('class', statusLabelClass.error);
            }
        }, function error(response) {
            $(statusLabel).html("An error occured during connecting to server. Please refresh and try again");
        });
    });
    /***************************************************/
    /*                Initiation                       */
    /***************************************************/
    //validation 
    $(form).validate({
        rules: {
            firstname: {
                required: true
            },
            lastname: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        }
    });
});