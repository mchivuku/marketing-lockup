@extends('signatureslayout')

@section('content')


<div class="extra-space bg-none section" id="content">
    <div class="row">
        <div class="layout">
           <div class="full-width">


                    <ul class="tabs" data-tab role="tablist">
                        @for($i = 0;$i<count($model->states);$i++)

                         <?php if($i==0){?>
                             <li class="tab-title active" role="presentation">
                                <a href="#{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}" role="tab" tabindex="{{$model->states[$i]['id']}}" aria-selected="true"
                                   data-target="signatures/getSignatures">{{$model->states[$i]['status']}}</a>
                            </li>

                         <?php }else{?>
                             <li class="tab-title" role="presentation">
                                <a href="#{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}" role="tab"
                                tabindex="{{$model->states[$i]['id']}}" aria-selected="true"
                                   data-target="signatures/getSignatures?status={{$model->states[$i]['id']}}">{{$model->states[$i]['status']}}</a>
                        </li>
                        <?php }?>
                        @endfor

                    </ul>

                        <div id="tabs-content" class="panel">

                                        <div class="content active">

                                             {!! $model->content !!}
                                        </div>



                            </div>


                    </div>


</div>
           </div>

    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.tab.js")}}"></script>


@endsection

