@extends('signatureslayout')

@section('content')



    <section class="collapsed bg-none section">
        <div class="row">
            <div class="layout">

                <div class="full-width">


                        {!! Form::open(array('url' => '/signatures/savesignature','id'=>"svgform",'method'=>'post')) !!}

                            <div class="form_errors">


                            </div>

                        @if (count($errors) > 0)
                                  <div data-alert class="alert-box alert radius">

                                        @foreach ($errors->all() as $error)
                                             {{ $error }} <br/>
                                        @endforeach

                                    <a href="#" class="close">&times;</a>
                                </div>

                             @endif


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
                                    {!!  Form::text('p',$model->primaryText,array('placeholder'=>'PRIMARY','maxlength'=>24)) !!}

                               </div>
                            </div>

                            <div class="row">
                                <div class="small-3 columns">
                                    {!!  Form::label('s', 'Secondary') !!}
                                </div>
                                <div class="small-9 columns">
                                    {!!  Form::text('s',$model->secondaryText,array('placeholder'=>'SECONDARY','maxlength'=>24)) !!}

                               </div>
                            </div>

                            <div class="row">
                                <div class="small-3 columns">
                                    {!!  Form::label('t', 'Tertiary') !!}

                                </div>
                                <div class="small-9 columns">
                                    {!!  Form::text('t',$model->tertiaryText,array('placeholder'=>'TERTIARY','maxlength'=>24)) !!}
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



                    </div>


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
            $('.form_errors').hide();

               formvalidate();
              $('#svgform input[type=text]').on('keyup', function (e) {


                    updatePreview(getTags());
                return;
            });

            $('#svgform input:radio').on('change', function (e) {
                updatePreview(getTags());
                return;
            });

        });

       function formvalidate(){
           $("#svgform").validate({

               rules: {
                   primaryText: {
                       maxlength: 24
                   },
                   secondaryText: {

                       maxlength: 24
                   },
                   tertiaryText: {
                       maxlength: 24
                   }
               },
                   messages: {

                       primaryText: {

                           maxlength: "Please enter primary text maximum length of 24 characters"
                       },
                       secondaryText: {
                           maxlength: "Please enter secondary text maximum length of 24 characters"
                       },
                       tertiaryText: {
                           maxlength: "Please enter tertiary text maximum length of 24 characters"
                       }
                   },
               errorPlacement: function(error, element) {

                   element.closest(".form_errors").append("<div data-alert class=\"alert-box alert " +
                           "radius\">"+error+"</div>");
                     $('.form_errors').show();
               },
               debug:true

               });
       }

        function updatePreview(tags){

            //validate
            //formvalidate();

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


            if(val==0){

                return {{(json_encode($model->getAllSchoolTags()))}};
            }

            else
                return {{json_encode($model->getNamedSchoolTags())}};




        }
    </script>
@endsection
