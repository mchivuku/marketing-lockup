@extends('signatureslayout')

@section('content')
    <style>
        img {
            width:200px;
        }

        .hover-border {
            border:5px solid #eee;
        }
        .hover-border:hover {
            /*border:5px solid #ccc;*/
        }
        .focused {
            border:5px solid #555;
        }
        form{
            margin-bottom: 20px;
        }
    </style>


                <div class="panel panel-default">
                    <div class="panel-heading">Signature Options</div>

                    <div class="panel-body">
                        <img src="{{{asset("img/option1.png")}}}" class="sigtype hover-border focused" data-v="1"/>
                        <img src="{{{asset("img/option2.png")}}}" class="sigtype hover-border" data-v="2"/>
                        <img src="{{{asset("img/option3.png")}}}" class="sigtype hover-border" data-v="3"/>
                        <img src="{{{asset("img/option4.png")}}}" class="sigtype hover-border" data-v="4"/>
                        <img src="{{{asset("img/option5.png")}}}" class="sigtype hover-border" data-v="5"/>
                        <img src="{{{asset("img/option6.png")}}}" class="sigtype hover-border" data-v="6"/>
                        <img src="{{{asset("img/option7.png")}}}" class="sigtype hover-border" data-v="7"/>
                        <br/>
                        <form id="svgform" action="{{url('/signatures/savesignature')}}" method="post">
                            <input type="hidden" id="hdnsignaturetype" value="1" name="hdnsignaturetype"/>
                            <label for="p">Primary</label><br/>
                            <textarea rows="4" cols="40" name="p">PRIMARY</textarea><br/>
                            <label for="s">Secondary</label><br/>
                            <textarea rows="4" cols="40" name="s">SECONDARY</textarea><br/>
                            <label for="t">Tertiary</label><br/>
                            <textarea rows="4" cols="40" name="t">Tertiary</textarea><br/>
                        <input type="button" value="Preview" id="previewButton"/>
                        <input type="reset"/>
                        <input type="submit" id="saveSignature" name="saveSignature" value="Save Changes"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </div>
                </div>

@endsection
@section('bodyScripts')
    <script src="{{{asset("js/scripts.js")}}}"></script>
    <script src="{{{asset("js/svg.js")}}}"></script>

@endsection
