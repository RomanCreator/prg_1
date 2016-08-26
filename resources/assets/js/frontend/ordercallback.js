$(document).ready(function () {
    $('body').find('.hospitals__item .checkin').bind('click', function() {
        var id = $(this).data('id');

        $.ajax({
            method: 'get',
            cache: false,
            url: '/researchesfor/'+id,
            context: this,
            success: function (data) {
                var window = new OrderWindow(false, false, data, id);
                window.show();
            },
            error: function (xhr) {
                console.log (xhr);
            }
        });

        return false;
    });

    $('body').find('.hospital-info .checkin').bind('click', function() {
        var id = $(this).data('id');

        $.ajax({
            method: 'get',
            cache: false,
            url: '/researchesfor/'+id,
            context: this,
            success: function (data) {
                var window = new OrderWindow(false, false, data, id);
                window.show();
            },
            error: function (xhr) {
                console.log (xhr);
            }
        });

        return false;
    });
});