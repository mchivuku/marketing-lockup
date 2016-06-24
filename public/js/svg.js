/* Foundation - Init */


$(document).foundation('reveal', 'reflow');

$(document).ready(function(){

    jQuery.fn.exists = function(){return this.length>0;}

    if (!$('aside').exists()){
      $('main').addClass('no-section-nav');
    }else{
         $('.alert-box').parents('section').addClass('section');
    }

    ModalWindow.init();
    AjaxTabs.init();

    // close alert box -
    $('.alert-box > a.close').click(function() { $(this).closest('[data-alert]').fadeOut(); });



});


/** Modal Window **/
ModalWindow = {

    settings: {
        button: $('a.modal'),
        destinationDiv: $("#viewModal")
    },

    init: function() {
        s = this.settings;
        this.bindUIActions();
    },

    bindUIActions: function() {
           s.button.on("click", function(event) {
               event.preventDefault();
               var link = $(this).attr('href');
               ModalWindow.loadModalWindow(link);
        });
    },

    loadModalWindow: function(link) {
        $.get(link,null,function(data){
            $("#viewModal").html(data);

        });
    }

};

AjaxTabs = {

    settings: {
        tab: $('ul.tabs li a'),
        destinationDiv: $('div#tabs-content')

    },

    init: function() {
        s = this.settings;
        this.bindUIActions();
    },

    bindUIActions: function() {
        s.tab.on("click", function(event) {
            var target = $(this).attr('data-target');
            var id = $(this).attr('href');

            $('li.tab-title').removeClass('active');

            $(this).parents('li').addClass('active');
            //remove active
            AjaxTabs.loadTabContent(target);



        });
    },

    loadTabContent: function(target) {
        $.get(target,null,function(data){

            $('div.active').empty().html(data);
             $('a.modal').on('click',function(event){
                event.preventDefault();
                var link = $(this).attr('href');
                $.get(link,null,function(data){
                    $('#viewModal').html(data);

                });
            });

            // initialize datatable
            $('#signatureTable').dataTable({  "ordering": false,"info":false});
           $('#signatureTable').table().data("table").refresh();

            $(document).foundation('reflow');

        });


    }

};
