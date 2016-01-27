@extends('signatureslayout')

@section('content')

    <?php


    $parameters =  array('primaryText'=>$model->primaryText,
            'secondaryText'=>$model->secondaryText,
            'tertiaryText'=>$model->tertiaryText,'named'=>$model->named,'campus'=>$model->campus);

    if(isset($editMode))
        $parameters['editMode']=$editMode;

    ?>

    <section class="collapsed bg-none section" id="content">
        <div class="row">
            <div class="layout">

                <div class="full-width" ng-app="addEditSignature">

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

                        <div class="button-group right">
                            <input type="submit" id="saveSignature" name="saveSignature"
                                   value="Confirm for Approval" class="button">
                            <input type="button"  class="button invert clear" value="Clear">

                        </div>

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
                               value="Confirm for Approval" class="button">
                        <input type="button" class="button invert clear" value="Clear">

                    </div>


                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("bower_components/angular/angular.min.js")}}"></script>

    <script type="text/javascript">

        angular.module('addEditSignature', []).run(['$rootScope',  function($rootScope) {

            // global initialize
            $rootScope.campus = "<?php echo $model->campus;?>";
            $rootScope.iupuiLikeCampuses=   <?php echo json_encode($iupuilikecampuses);?>  ;
            $rootScope.allcampuses=  <?php echo json_encode($allcampuses);?>  ;


            $rootScope.named = "<?php echo isset($model->named)?$model->named:1;?>";

            $rootScope.toggle_named_school_buttons = function(toggle){

                // initialize
                if(toggle == undefined)
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

                    $.get('allcampusform',elements,function(data){
                        $('#loadform').empty().append(data);
                        initializeInput();
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
                });

                $('#svgform').validate();

            };

            angular.element(document).ready(function(){

                $('#svgform input[type=text]').on('keyup', function (e) {
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

                    console.log($.isEmptyObject($(data).find('svg')));
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

                $('#svgform input[type=text]').on('keyup', function (e) {
                    loadPreview();
                });
            };


        }]);


    </script>
@endsection