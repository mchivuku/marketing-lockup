@extends('signatureslayout')

@section('content')
    <style>
        svg {
            width:350px;

        }

        svg:last-child {
            margin-bottom: 20px;
        }

        div#example-images{
            border: 5px solid #eee;
            padding:10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
    </style>

    <section class="section page-title bg-none">
        <div class="row">
            <div  class="layout">
                <h1>{{$title}}</h1></div>
        </div>
    </section>
    <section class="collapsed bg-none section">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                       <div id="example-images">

                            {!!  $model->getSignaturePreview() !!}
                        </div>


                        <form id="svgform" action="{{url('/signatures/savesignature')}}" method="post">


                            <div class="row">
                                <div class="small-3 columns">
                                    <label for="named">Named School</label></div>
                                <div class="small-9 columns">
                                    <input type="radio" name="named" value="1" id="named"><label
                                            for="namedschool">Yes</label>
                                    <input type="radio" name="named" checked value="0" id="allschool"><label
                                            for="allschool">No</label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="small-3 columns">
                                    <label for="p">Primary</label></div>
                                <div class="small-9 columns">
                                    <textarea rows="4" cols="40" name="p">{{$model->primaryText}}</textarea></div>
                            </div>

                            <div class="row">
                                <div class="small-3 columns">
                                    <label for="s">Secondary</label>
                                </div>
                                <div class="small-9 columns">
                                    <textarea rows="4" cols="40" name="s">{{$model->secondaryText}}</textarea></div>
                            </div>

                            <div class="row">
                                <div class="small-3 columns">
                                    <label for="t">Tertiary</label>
                                </div>
                                <div class="small-9 columns">
                                    <textarea rows="4" cols="40" name="t">{{$model->tertiaryText}}</textarea>
                                </div>
                            </div>


                            <div class="button-group right">
                                 <input type="submit" id="saveSignature" name="saveSignature"
                                       value="Save Changes" class="small button"/></li>
                                <input type="reset" class="small button secondary"/></li>

                            </div>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>

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
