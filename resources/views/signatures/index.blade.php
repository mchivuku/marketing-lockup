@extends('app')

@section('content')


    <div class="extra-space bg-none section" id="content">
        <div class="row">
            <div class="layout">
                <div class="full-width">


                    <ul class="tabs" data-tab role="tablist">
                        @for($i = 0;$i<count($model->states);$i++)

                            @if($i == 0)
                            <li class="tab-title active" role="presentation">
                                <a href="#{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}" role="tab"
                                   tabindex="{{$model->states[$i]['id']}}" aria-selected="true"
                                   data-target="{{Url::to("getsignatures")}}">{{$model->states[$i]['status']}}</a>
                            </li>

                           @else
                            <li class="tab-title" role="presentation">
                                <a href="#{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}" role="tab"
                                   tabindex="{{$model->states[$i]['id']}}" aria-selected="true"
                                   data-target="{{Url::to("getsignatures")."?status=".$model->states[$i]['id']}}">{{$model->states[$i]['status']}}</a>
                            </li>
                            @endif
                        @endfor

                    </ul>

                    <div id="tabs-content" class="panel">

                        <div class="content active">

                            {!! $model->content !!}
                        </div>


                    </div>

                    @if($model->isAdmin)
                        <a href="{{url("/admin.html")}}"class="button right">
                           Admin
                        </a>
                    @endif

                </div>


            </div>
        </div>

    </div>

@endsection

