@extends('signatureslayout')

@section('content')
    <style>
        img {
            width: 200px;

        }

        img:last-child {

            margin-bottom: 20px;
        }

        .hover-border {
            border: 5px solid #eee;
        }

        form {
            margin-bottom: 20px;
        }
    </style>

    <section class="section page-title bg-none">
        <div class="row">
            <div
                    class="layout">
                <h1>{{$title}}</h1></div>
        </div>
    </section>
    <section class="collapsed bg-none section">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                        <div id="example-images">
                            @include('all-school-image-examples')
                        </div>

                        <form id="svgform" action="{{url('/signatures/savesignature')}}" method="post">


                            <div class="row">
                                <div class="small-3 columns">
                                    <label for="named">Named School</label></div>
                                <div class="small-9 columns">
                                    <input type="radio" name="named" value="1" id="named"><label
                                            for="namedschool">Yes</label>
                                    <input type="radio" name="named" value="0" id="allschool"><label
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
                                <input type="button" value="Preview" id="previewButton" class="small button"/></li>
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
            // radio button to show example images
            $('form input:radio').on('change',function(){
                var val= $(this).val();
                var getUrl;
                if(val==0)
                 getUrl = "{{url("signatures/allschoolimages") }}";
                else
                    getUrl ="{{url("signatures/namedschoolimages")}}";

                $.get(getUrl,null,function(data){
                    $('div#example-images').html(data);
                });
            });

            $('#previewButton').click(function (e) {
                e.preventDefault();
                $('#signature-preview').empty();

                $('#example-images img').each(function(){
                    var tag = $(this).attr('data-v');
                    var qstr = $('#svgform').serialize() + '&v=' + tag;
                    var src = 'https://iet.communications.iu.edu/mercerjd/svg/s.php?'+qstr;
                    $.get(src, function(data) {
                        $('#signature-preview').append(data)
                    });

                });
                return;
            });
        });



    </script>
@endsection
