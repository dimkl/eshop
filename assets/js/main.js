(function ($) {
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
    $.fn.ajaxFormSubmit = function (ajaxSettings, successCallback, errorCallback) {
        if (!$(this).is('form')) {
            throw new Exception('Element is not a form.');
        }
        if (typeof ajaxSettings !== "object") {
            throw new Exception('ajaxData must typeof object');
        }
        if (typeof ajaxSettings.url === "undefined") {
            throw new Exception('ajaxData must have an "url" property');
        }
        if (typeof successCallback !== "function") {
            successCallback = function (response) {
                console.log('done');
                console.log(response);
            };
        }
        if (typeof errorCallback !== "function") {
            errorCallback = function (response) {
                console.log('fail', arguments);
                console.log(response);
            };
        }
        var defaultAjaxHeaders = {
            contentType: "application/json; charset=UTF-8"
        }
        var serializedData = $(this).serializeObject();
        var stringifiedData = "";
        //check if serializedData is json
        try {
            stringifiedData = JSON.stringify(serializedData);
        } catch (e) {
            throw new Exception('Element is not a form.');
        }
        ajaxSettings = $.extend(ajaxSettings, defaultAjaxHeaders, {'data': stringifiedData});
        $.ajax(ajaxSettings)
                .done(successCallback)
                .fail(errorCallback);
    };

})($);