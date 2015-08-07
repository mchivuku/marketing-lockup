@extends('signatureslayout')

@section('content')

<meta name="_token" content="{
    { csrf_token() }}" />


<div class="extra-space bg-none section" id="content">
    <div class="row">
        <div class="layout">
           <div class="full-width">
                <div class="text">

                    <ul class="tabs" data-tab role="tablist">
                        @for($i = 0;$i<count($model->states);$i++)

                         <?php if($i==0){?>
                             <li class="tab-title active" role="presentation">
                                <a href="#{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}" role="tab" tabindex="{{$model->states[$i]['id']}}" aria-selected="true"
                                   aria-controls="all-signatures"
                                   data-target="signatures/getSignatures">{{$model->states[$i]['status']}}</a>
                            </li>

                         <?php }else{?>
                             <li class="tab-title" role="presentation">
                                <a href="#{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}" role="tab"
                                tabindex="{{$model->states[$i]['id']}}" aria-selected="true"
                                   data-target="signatures/getSignatures?status={{$model->states[$i]['id']}}".aria-controls="all-signatures">{{$model->states[$i]['status']}}</a>
                        </li>
                        <?php }?>
                        @endfor

                    </ul>

                        <div id="tabs-content">

                            @for($i = 0;$i<count($model->states);$i++)

                                    <?php if($i==0){?>
                                        <div id="{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}"
                                             class="content active">

                                             {!! $model->content !!}
                                        </div>

                                <?php }else{?>

                                <div role="tabpanel" aria-hidden="false" class="content"
                                         id="{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}"></div>

                                <?php }?>

                            @endfor
                            </div>
                        </div>

                    </div>

                 </div>
               </div>
           </div>
    </section>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.tab.js")}}"></script>


@endsection

