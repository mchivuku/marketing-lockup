@extends('signatureslayout')

@section('content')

<meta name="_token" content="{
    { csrf_token() }}" />


<section class="section page-title bg-none">
    <div class="row"><div class="layout">
            <h1>Signatures</h1>
        </div>
    </div>
</section>
<section class="extra-space bg-none section" id="content">
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
                                        <section role="tabpanel" aria-hidden="false" class="content active" id="{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}">

                                             {!! $model->content !!}
                                        </section>

                                <?php }else{?>

   <section role="tabpanel" aria-hidden="false" class="content" id="{{str_replace(" ","-",strtolower($model->states[$i]['status']))}}"></section>

                                <?php }?>

                            @endfor
                        </div>



                    </div>

                 </div>
               </div>
           </div>
    </section>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.tab.js")}}"></script>
    <script type="text/javascript">
        $(document).foundation('reveal', 'reflow');

        $('ul.tabs li a').on('click',function()
        {
            var id = $(this).attr('href');
            var target = $(this).attr('data-target');

            $('ul.tabs li').removeClass('active');
            $(this).parent('li').addClass('active');
            $('div#tabs-content').find('section.active').html("");
            $('div#tabs-content').find('section.active').removeClass('active');


            $.get(target,null,function(data){

                $('section'+id).html(data);
                $('section'+id).addClass('active');

                $('a.modal').on('click',function(event){
                    event.preventDefault();
                    var link = $(this).attr('href');
                    $.get(link,null,function(data){
                        console.log(data);
                        $('#viewModal').html(data);
                    });
                });
                $(document).foundation('reveal', 'reflow');

            });

        });

    </script>

@endsection

