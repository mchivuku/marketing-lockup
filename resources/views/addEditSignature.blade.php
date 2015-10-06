@extends('signatureslayout')

@section('content')

    <section class="collapsed bg-none section" id="content">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                        {!! Form::open(array('url' => '/signatures/savesignature','id'=>"svgform",'method'=>'post')) !!}


                            {!!  Form::label('named', 'Named School') !!}
                            {!!  Form::radio('named',1) !!}
                            {!!  Form::label('namedschool', 'Yes') !!}
                            {!!  Form::radio('named',0,array('checked'=>'checked')) !!}
                            {!!  Form::label('namedschool', 'No') !!}


                            <label for="primary">Primary (required) <br/><span class="help-text"id="replace-primary">(ex. Medicine, Psychology)</span>
                                <input id="primary" name="p" placeholder='PRIMARY'  type="text" required  maxlength="51"   maxLen="50"
                                       value="{{$model->primaryText}}">
                            </label>


                                    <label for="secondary">Secondary<br/><span
                                                class="help-text"id="replace-secondary">(ex. School of, Department of)</span>
                                        <input id="secondary" name="s"   type="text" placeholder='SECONDARY'  maxlength="51"   maxLen="50"
                                               value="{{$model->secondaryText}}">
                                    </label>




                                 <label for="tertiary">Tertiary<br/><span class="help-text">(ex. Bloomington,
                                       Indianapolis)</span>
                                  <input id="tertiary" name="t"  type="text" placeholder='Tertiary'  maxlength="51"
                                maxLen="50" value="{{$model->tertiaryText}}"></label>



                                   {!!  Form::label('type', 'Lock-up Orientation') !!}
                                     {!!  Form::radio('type','',array('checked'=>'checked')) !!}
                                     {!!  Form::label('svgType ', 'All') !!}
                                     {!!  Form::radio('type','h') !!}
                                     {!!  Form::label('svgType ', 'Horizontal') !!}
                                     {!!  Form::radio('type','v') !!}
                                     {!!  Form::label('svgType ', 'Vertical') !!}



                            <div class="button-group right">
                                <input type="submit" id="saveSignature" name="saveSignature"
                                       value="Submit for Approval" class="small button">

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
                               value="Submit for Approval" class="small button">

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

            $('#svgform input:radio').on('change', function (e) {
                update_label_on_toggle($(this).val());
                updatePreview();
                return;
            });

            $('.clear-button').click(function(event){
                event.preventDefault();
                $('#svgform input[type=text]').val('');
                $('#signature-preview').empty('');
                $('#duplicateButtons').hide();

            });
        });

        function updatePreview(){
            var form = $('#svgform');
            $('#duplicateButtons').hide();
            if(form.valid()){
                $.get('getPreview',form.serialize(),function(data){
                    $('div#signature-preview').empty().append("<div id='example-images'>"+data+'</div>');
                    console.log(data.length);
                    if(data.length>0){
                        $('#duplicateButtons').show();
                    }

                });
            }

        }

        function update_label_on_toggle(toggle){
            if(toggle==0){
                $('#replace-primary').html('(ex. Medicine, Psychology)');
                $('#replace-secondary').html('(ex. School of, Department of)');
            }else{
                $('#replace-primary').html('(ex. Kelley, McKinney)');
                $('#replace-secondary').html('(ex. School of Business, School of Law)');

            }

        }
    </script>
    @endsection