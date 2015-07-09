$(document).ready(function () {
    $('#previewButton').click(function (e) {
        e.preventDefault();
        var tag = $('.focused').attr('data-v');
        var qstr = $('#svgform').serialize() + '&v=' + tag;
        var src = 'https://iet.communications.iu.edu/mercerjd/svg/s.php?'+qstr;
        console.log(src);
        $.get(src, function(data) {
            $('.panel-body').find('svg').remove();
            $('.panel-body').append(data);
        });

        return;
    });

    //Modal window
    $('[data-toggle="modal"]').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
         $.get(url, function(data) {
            $(data).modal();
        });
    });


});
