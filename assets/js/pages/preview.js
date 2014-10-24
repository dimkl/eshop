$(document).ready(function () {
    /***************************************************/
    /*                Setup                            */
    /***************************************************/
    //initialization of settings
    var form = $('#commentForm');
    var formPanel = $('#commentForm-panel');
    var statusLabel = $('#statusLabel', form);
    var commentPanel = $('#commentPanel');
    var commentTemplate = $('#commentTemplate').html();
    var statusLabelClass = {
        'error': 'col-xs-12 label label-danger',
        'success': 'col-xs-12 label label-success'
    };
    /**
     * function to initiate 2 types of raty rating, the readOnly and the normal
     */
    function ratyInit() {
        $('.raty').raty({
            scoreName: "rating"
        });
        $('.raty-readonly').raty({
            readOnly: true,
            score: function () {
                return $(this).attr('data-score');
            }
        });
    }
    /**
     * Click handler for comment button, used to submit data with ajax to server
     */
    $('#commentBtn').on('click', function () {
        //validation check
        if (!$(form).valid()) {
            return false;
        }
        //ajax submit
        $(form).ajaxFormSubmit({type: "POST", url: "ajax/comment/create"},
        function success(response) {
            try {
                response = JSON.parse(response);
            } catch (e) {
                throw "Invalid response. Response must be json";
            }
            if (response.status === "error") {
                $(statusLabel).html(response.errormessage);
                $(statusLabel).attr('class', statusLabelClass.error);
            }
            else if (response.status === "success") {
                $(statusLabel).html(response.data.message);
                $(statusLabel).attr('class', statusLabelClass.success);
                //append comment
                var template = _.template(commentTemplate);
                var html = template(response.data);
                $(html).prependTo(commentPanel);
                //
                ratyInit();
                //remove commentForm
                $(formPanel).remove();
            }
        }, function error() {
            $(statusLabel).html("An error occured during connecting to server. Please refresh and try again");
        });
    });

    /***************************************************/
    /*                Initiation                       */
    /***************************************************/

    //initiate Raty
    ratyInit();
    //validation 
    $(form).validate({
        rules: {
            content: {
                required: true,
                rangelength: [0, 1000]
            },
            rating: {
                required: true
            }
        }
    });
});
