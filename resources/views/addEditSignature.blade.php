@extends('signatureslayout')

@section('content')

    <section class="collapsed bg-none section">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                        {!! Form::open(array('url' => '/signatures/savesignature','id'=>"svgform",'method'=>'post')) !!}

                          <div class="row">
                                <div class="small-3 columns">
                                    {!!  Form::label('named', 'Named School') !!}
                                </div>
                                <div class="small-9 columns">
                                    {!!  Form::radio('named',1) !!}
                                    {!!  Form::label('namedschool', 'Yes') !!}
                                    {!!  Form::radio('named',0,array('checked'=>'checked')) !!}
                                    {!!  Form::label('namedschool', 'No') !!}
                                </div>
                            </div>

                    <div class="row">
                        <div class="small-3 columns">
                            <label for="primary">Primary (required)</label>
                        </div>
                        <div class="small-9 columns">
                            <input id="primary" name="p" placeholder='PRIMARY'  maxlength="25"  type="text" required
                                   maxLen="24"    value="{{$model->primaryText}}">

                        </div>
                    </div>


                            <div class="row">
                                <div class="small-3 columns">
                                    <label for="secondary">Secondary</label>
                                </div>
                                <div class="small-9 columns">
                                    <input id="secondary" name="s" maxlength="25"  type="text" placeholder='SECONDARY'
                                           value="{{$model->secondaryText}}"   maxLen="24">

                               </div>
                            </div>

                            <div class="row">
                                <div class="small-3 columns">
                                    <label for="tertiary">Tertiary</label>
                                </div>
                                <div class="small-9 columns">
                                    <input id="tertiary" name="t"  maxlength="25" type="text" placeholder='Tertiary'
                                           value="{{$model->tertiaryText}}"   maxLen="24">
                                </div>
                            </div>

                            <div class="button-group right">
                                <input type="submit" id="saveSignature" name="saveSignature"
                                       value="Save Changes" class="small button">
                                <input type="reset" class="small button secondary" value="Clear">

                            </div>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                {!! Form::close() !!}

                    <!-- signature preview -->
                    <div id="signature-preview">
                        @if($editmode)
                            <div id='example-images'>
                                {!!$model->getSignaturePreview()!!}
                            </div>
                        @endif

                    </div>


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
                //console.log('element= ' + $(element).attr('name') + ' param= ' + param )
                if ($(element).val().length > param) {
                    return false;
                } else {
                    return true;
                }
            }, "You have reached the maximum number of characters allowed for this field.");



            $('#svgform input[type=text]').on('keyup', function (e) {
                updatePreview(getTags());
                return;
            });

            $('#svgform input:radio').on('change', function (e) {
                updatePreview(getTags());
                return;
            });

        });

        function updatePreview(tags){
            var form = $('#svgform');
            if(form.valid()){
                $.get('getPreview',form.serialize(),function(data){
                    $('div#signature-preview').empty().append("<div id='example-images'>"+data+'</div>');
                });
            }

        }

        function getTags(){
            // radio button to show example images
            var val= $('form input:radio:checked').val();
            if(val==0)
                return {{(json_encode($model->getAllSchoolTags()))}};
            return {{json_encode($model->getNamedSchoolTags())}};

        }
    </script>
    @endsection