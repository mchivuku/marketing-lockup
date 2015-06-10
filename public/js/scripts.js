$(document).ready(function () {
	addImageClicks();
    });

function addImageClicks() {
    $('img').on('click', function (e) {
	    console.log(e.currentTarget);
	    $('img').removeClass('focused');
	    $(e.currentTarget).addClass('focused');
        console.log($(this).attr("data-v"));
        $('#hdnsignaturetype').val($(this).attr('data-v'));
	});
}