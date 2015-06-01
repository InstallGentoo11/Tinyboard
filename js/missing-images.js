$(document).ready(function() {

    $('img').each(function() {
        var img = $(this);

        img.one('error', function() {
            img.prop('src', '/static/deleted.png');
            img.width('auto');
            img.height('auto');
        });
    });

});
