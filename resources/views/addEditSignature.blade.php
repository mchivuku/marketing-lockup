@extends('signatureslayout')

@section('content')

    <?php


    $parameters =  array('primaryText'=>$model->primaryText,
            'secondaryText'=>$model->secondaryText,
            'tertiaryText'=>$model->tertiaryText,'named'=>$model->named,'campus'=>$model->campus);

    if(isset($editMode))
        $parameters['editMode']=$editMode;

    ?>

    <section class="collapsed bg-none section" id="content" ng-app="addEditSignature">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                    <div id="errorMessage"></div>

                    {!! Form::open(array('url' => '/signatures/confirmsignature','id'=>"svgform",'method'=>'post')) !!}

                    {!!  Form::label('campus', 'Campus') !!}

                    {!!  Form::select('campus',
                       $allcampuses,
                       $model->campus,
                       ['ng-model'=>'campus','ng-change'=>'initform()'])
                    !!}

                    <div ng-show="campus.length>0">
                        <div ng-if="!find(campus,iupuiLikeCampuses)">
                            {!!  Form::label('named', 'Named School') !!}

                            {!!  Form::radio('named',1,null,['ng-model'=>'named','ng-change'=>'toggle_named_school_buttons(named)','ng-checked'=>$model->named]) !!}
                            {!!  Form::label('namedschool', 'Yes') !!}

                            {!!  Form::radio('named',0,null,['ng-model'=>'named','ng-change'=>'toggle_named_school_buttons(named)','ng-checked'=>$model->named]) !!}
                            {!!  Form::label('namedschool', 'No') !!}

                        </div>

                        <div ng-if="find(campus,iupuiLikeCampuses)">
                            {!!  Form::hidden('named',1,['ng-model'=>'named'])!!}

                        </div>

                        <div id="loadform">
                            @if(in_array($model->campus,$iupuilikecampuses))
                                @include("..includes.iupui-like-addEditSignature",$parameters)
                            @else
                                @include("..includes.allcampus-addEditSignature",$parameters)
                            @endif
                        </div>


                        @if($model->signatureid)
                            <input type="hidden" name="signatureid" value="{{$model->signatureid }}">
                        @endif




                        <div class="grid right">
                             <input type="submit" id="saveSignature" name="saveSignature"
                                        value="Confirm for Approval" class="button">
                            <input type="button"  class="button invert clear" value="Clear">

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


                        <div class="right grid" id="duplicateButtons" style="display:none;">
                            <input type="submit" id="saveSignature" name="saveSignature"
                                       value="Confirm for Approval" class="button">
                           <input type="button" class="button invert clear" value="Clear">

                        </div>



                        {!! Form::close() !!}

                    </div>



                </div>

                <div ng-show="campus.length>0" style="clear: both;padding-top:20px;">
                    <p class="subheader lockup-notes">If your unit has special characters, such as an accent or surname prefix (Mc, Mac, or De), <a class="email" href="mailto:{{$contactMail}}" rel="nofollow">contact IU Communications </a>for a custom marketing lockup.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.4/angular.min.js"></script>

    <script type="text/javascript">

        angular.module('addEditSignature', []).run(['$rootScope',  function($rootScope) {

            // global initialize
            $rootScope.campus = "<?php echo $model->campus;?>";
            $rootScope.iupuiLikeCampuses=  <?php echo json_encode($iupuilikecampuses);?>  ;
            $rootScope.allcampuses=  <?php echo json_encode($allcampuses);?>  ;

            $rootScope.named = "<?php echo isset($model->named)?$model->named:0;?>";

            $rootScope.toggle_named_school_buttons = function(toggle){

                // initialize
                if(toggle === 'undefined')
                    toggle = 1;


                var elements = buildFormElements();

                if(toggle==0){

                    $.get('allschool',elements,function(data){
                        $('.toggleElements').empty().append(data);
                        initializeInput();
                        loadPreview();
                    });

                }else{
                    $.get('namedschool',elements,function(data){
                        $('.toggleElements').empty().append(data);
                        initializeInput();
                        loadPreview();
                    });
                }
            };

            $rootScope.find = function(needle,haystack){

                // convert json object to json array
                var arr = $.map(haystack, function(el) { return el });

                for(i=0;i<arr.length;i++){

                    if(arr[i]==needle)
                        return true;
                }
                return false;

            };



            $rootScope.initform= function(){

                angular.element('#svgform input[type=text]:not(.default-primary)').val('');
                var elements = buildFormElements();


                if($rootScope.find($rootScope.campus,$rootScope.iupuiLikeCampuses)){
                    $.get('iupuiform',elements,function(data){
                        $('#loadform').empty().append(data);
                        initializeInput();
                        angular.element('#svgform input[type=text]:not(.default-primary)').val('');
                        angular.element('#signature-preview').empty('');
                        angular.element('#duplicateButtons').hide();
                    });


                }else{
                    elements[2].value=0;
                    $.get('allcampusform',elements,function(data){
                        $('#loadform').empty().append(data);
                        initializeInput();
                        $('input[name="named"][value="' + 0 + '"]').prop('checked', true);
                        $rootScope.named=0;
                        angular.element('#svgform input[type=text]:not(.default-primary)').val('');
                        angular.element('#signature-preview').empty('');
                        angular.element('#duplicateButtons').hide();
                    });

                }

                angular.element('.clear').click(function (event) {
                    event.preventDefault();
                    angular.element('#svgform input[type=text]:not(.default-primary)').val('');
                    angular.element('#signature-preview').empty('');
                    angular.element('#duplicateButtons').hide();
                    $('input[name="named"][value="' + 0 + '"]').prop('checked', true);
                    $rootScope.named=0;
                });

                $('#svgform').validate();

            };

            angular.element(document).ready(function(){


                $('#primary, #secondary, #tertiary').on('input keyup', function (e) {
                    loadPreview();
                });


                angular.element('.clear').click(function (event) {
                    event.preventDefault();
                    angular.element('#svgform input[type=text]:not(.default-primary)').val('');
                    angular.element('#signature-preview').empty('');
                    angular.element('#duplicateButtons').hide();
                });

                $rootScope.$apply();
            });

            var loadPreview = function(){

                var elements = buildFormElements();

                $('#duplicateButtons').hide();

                $.get('getPreview',elements,function(data){

                    $('div#signature-preview').empty().append("<div id='example-images'>"+data+'</div>');

                    if($(data).find('svg').length>0){
                        $('#duplicateButtons').show();
                    }
                });



            };

            var buildFormElements = function(){
                var elements = $( '#svgform' ).serializeArray();
                return elements;
            };


            var initializeInput = function(){

                $('#primary, #secondary, #tertiary').on('input keyup', function (e) {
                    loadPreview();
                });

            };


        }]);


    </script>
@endsection