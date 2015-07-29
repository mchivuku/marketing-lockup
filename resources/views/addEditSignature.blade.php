@extends('signatureslayout')

@section('content')



    <section class="collapsed bg-none section">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                       <div id="example-images">

                            {!!  $model->getSignaturePreview() !!}
                        </div>


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
                                    {!!  Form::label('p', 'Primary') !!}
                                 </div>
                                <div class="small-9 columns">
                                    {!!  Form::textarea('p', $model->primaryText,array('rows'=>4,'cols'=>40,'name'=>'p')) !!}
                               </div>
                            </div>

                            <div class="row">
                                <div class="small-3 columns">
                                    {!!  Form::label('s', 'Secondary') !!}
                                </div>
                                <div class="small-9 columns">
                                    {!!  Form::textarea('s', $model->secondaryText,array('rows'=>4,'cols'=>40,'name'=>'s')) !!}
                               </div>
                            </div>

                            <div class="row">
                                <div class="small-3 columns">
                                    {!!  Form::label('t', 'Tertiary') !!}

                                </div>
                                <div class="small-9 columns">
                                    {!!  Form::textarea('t', $model->tertiaryText,array('rows'=>4,'cols'=>40,'name'=>'t')) !!}
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
                    <div id="signature-preview"></div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script src="{{asset("js/scripts.js")}}"></script>
    <script src="{{asset("js/svg.js")}}"></script>

    <script type="text/javascript">

        $(document).ready(function(){

            $('#svgform textarea,form input:radio').on('change', function (e) {
                updatePreview(getTags());
                return;
            });

        });

        function updatePreview(tags){

            $('div#example-images').empty();

            for(i=0;i<tags.length;i++){

                 var qstr = $('#svgform').serialize() + '&v=' + tags[i];
                   var src = 'https://iet.communications.iu.edu/mercerjd/svg/s.php?'+qstr;
                   $.get(src, function(data) {

                    $('div#example-images').append(data)
                });

            }
        }

        function getTags(){

            // radio button to show example images
            var val= $('form input:radio:checked').val();


            if(val==0){

                return {{(json_encode($model->getAllSchoolTags()))}};
            }

            else
                return {{json_encode($model->getNamedSchoolTags())}};




        }
    </script>
@endsection
