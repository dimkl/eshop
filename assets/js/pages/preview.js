$(document).ready(function () {
    $('.raty').raty({
        score: function () {
            return $(this).attr('data-score');
        }
    });
});
