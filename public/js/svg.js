$(document).ready(function(){
    jQuery.fn.exists = function(){return this.length>0;}
    if (!$('aside').exists()){
      $('main').addClass('no-section-nav');
    }

    $('a.modal').on('click',function(event){

        event.preventDefault();
        var link = $(this).attr('href');

        $.get(link,null,function(data){

            $('#viewModal').html(data);

        });

    });

    // close alert box
    $('.alert-box > a.close').click(function() { $(this).closest('[data-alert]').fadeOut(); });

});