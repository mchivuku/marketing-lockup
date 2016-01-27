@extends('signatureslayout')

@section('content')

    <section class="collapsed bg-none section" id="content">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                        {!! Form::open(array('url' => '/signatures/confirmsignature','id'=>"svgform",'method'=>'post')) !!}

                        {!!  Form::label('named', 'Named School') !!}
                    @if($editmode)
                       @if($model->named==1)
                            {!!  Form::radio('named',1,array('checked'=>'checked')) !!}
                            {!!  Form::label('namedschool', 'Yes') !!}
                            {!!  Form::radio('named',0) !!}
                            {!!  Form::label('namedschool', 'No') !!}
                       @else
                            {!!  Form::radio('named',1) !!}
                            {!!  Form::label('namedschool', 'Yes') !!}
                            {!!  Form::radio('named',0,array('checked'=>'checked')) !!}
                            {!!  Form::label('namedschool', 'No') !!}
                       @endif

                     @else
                        {!!  Form::radio('named',1) !!}
                        {!!  Form::label('namedschool', 'Yes') !!}
                        {!!  Form::radio('named',0,array('checked'=>'checked')) !!}
                        {!!  Form::label('namedschool', 'No') !!}
                    @endif

                    <div id="toggleElements">
                        @if($model->named && $model->named==1)
                            @include("...includes.named-school-form",
                       array('primaryText'=>$model->primaryText,'secondaryText'=>$model->secondaryText,
                      'tertiaryText'=>$model->tertiaryText))
                        @else
                            @include("...includes.non-named-school-form",
                       array('primaryText'=>$model->primaryText,'secondaryText'=>$model->secondaryText,
                      'tertiaryText'=>$model->tertiaryText))
                        @endif

                    </div>

                    @if($model->signatureid)
                        <input type="hidden" name="signatureid" value="{{$model->signatureid }}">
                    @endif

                            <div class="button-group right">
                                <input type="submit" id="saveSignature" name="saveSignature"
                                       value="Confirm for Approval" class="small button">
                                <input type="reset"  class="small button secondary clear-button" value="Clear">

                            </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                    <!-- signature preview -->
                    <div id="signature-preview">
                        @if($editmode)
                            <div id='example-images'>
                                {!!$model->getSignaturePreview()!!}
                            </div>

                        @endif

                    </div>


                    <div class="button-group right" id="duplicateButtons" style="display:none;">
                        <input type="submit" id="saveSignature" name="saveSignature"
                               value="Confirm for Approval" class="small button">

                        <input type="reset" class="small button secondary clear-button" value="Clear">

                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#svgform').validate();

            initializeformToggleInputs();

            $('#svgform input:radio').on('change', function (e) {
                update_form_elements($(this).val());

                return;
            });

            $('.clear-button').click(function(event){
                event.preventDefault();
                $('#svgform input[type=text]').val('');
                $('#signature-preview').empty('');
                $('#duplicateButtons').hide();
            });
        });


        function initializeformToggleInputs(){

            jQuery.validator.addMethod("maxLen", function (value, element, param) {
                if($(element).val().length > param) {
                    return false;
                } else {
                    return true;
                }
            }, "You have reached the maximum number of characters allowed for this field.");


            $('#svgform input[type=text]').on('keyup', function (e) {
                updatePreview();
                return;
            });

        }


        function updatePreview(){
            var form = $('#svgform');
            $('#duplicateButtons').hide();

            if(form.valid()){
                $.get('getPreview',form.serialize(),function(data){
                    $('div#signature-preview').empty().append("<div id='example-images'>"+data+'</div>');
                    if(data.length>0){
                        $('#duplicateButtons').show();
                    }

                });
            }else{
                $('div#signature-preview').empty().append("<div id='example-images'></div>");
            }

        }

        function update_form_elements(toggle){
            var elements = $( '#svgform' ).serializeArray();

            if(toggle==0){
                $.get('allschool',elements,function(data){
                    $('#toggleElements').empty().append(data);
                    initializeformToggleInputs();
                    updatePreview();
                });

            }else{
                $.get('namedschool',elements,function(data){
                    $('#toggleElements').empty().append(data);
                    initializeformToggleInputs();
                    updatePreview();
                });
            }
        }
    </script>
@endsection