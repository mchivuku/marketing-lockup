@extends('app')

@section('content')

    <?php include "email-lockup.html";?>
@endsection


@section('scripts')
    <script type="text/javascript" src="{{asset("bower_components/angular/angular.min.js")}}"></script>
    <!--script src="https://jqueryvalidation.org/files/dist/additional-methods.min.js"></script-->

    <script type="text/javascript">
        $(document).ready(function(){
            $('#signatureForm').validate({
                rules: {
                    field: {
                        required: true,
                        phoneUS: true
                    }
                }
            });


            $('input[name="logos"]').unbind('click').click(function(){
                var selectedRadio = $('input[name="logos"]:checked').val();
                var selectorString ="img[for-radio='" + selectedRadio + "']:first" ;
                var html = $(selectorString).clone();

                $('#signatureOutput').find('#logoDiv').empty();
                $('#signatureOutput').find('#logoDiv').html(html);
                $('#signatureOutput').find('#logoDiv').find('img').show();


            });


            $(".previewHeading").hide();
            $('#lnkEditFields').hide();
            $('#signatureOutputWithoutImage').hide();
            $('#signatureOutputWithImage').hide();
            $('#signatureForm').validate(
                    {
                        errorClass: "field-error"
                    }
            );
            $.validator.messages.required = '*Required';
            $('#generateSignature').click(function(){

                if ( $('#signatureForm').valid()){

                    $('#signatureOutput').hide();

                    $('#lnkEditFields').show();
                    $(".previewHeading").show();

                    $('#mainForm').hide();

                    $('#signatureOutputWithoutImage').children().remove();
                    $('#signatureOutputWithoutImage').append($('#signatureOutput').html());
                    $("#signatureOutputWithoutImage").find('div#logoDiv').remove();
                    $('#signatureOutputWithoutImage').show();

                    $('#signatureOutputWithImage').children().remove();
                    $('#signatureOutputWithImage').append($('#signatureOutput').html());
                    $('#signatureOutputWithImage').show();
                    $('.copy-instructions').show();

                    $('#previewDiv').show();
                    /*
                     $('#signatureOutputWithImage').find('p').each(function(k, v){
                     var innerHTML = $(this).html();
                     if ( $.trim(innerHTML) == "" ){
                     $(this).remove();
                     }
                     }) ;*/

                    /*                $('#signatureOutputWithoutImage').find('p').each(function(k, v){
                     var innerHTML = $(this).html();
                     if ( $.trim(innerHTML) == "" ){
                     $(this).remove();
                     }
                     }) ;

                     */

                    $('#lnkEditFields').click(function(){
                        $('#mainForm').show();
                        $('#previewText').html('Marketing Lock-Up Preview');
                        $('#signatureOutputWithoutImage').hide();
                        $('#signatureOutputWithImage').hide();
                        $('#signatureOutput').show();
                        $('#previewDiv').hide();
                        $('.copy-instructions').hide();

                        $(this).hide();
                    })
                }

            });

        });
        //
    </script>
@endsection