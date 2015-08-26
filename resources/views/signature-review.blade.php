@extends('signatureslayout')
@section('content')

<?php

    \Html::macro('flash',
    function ($message, $type) {

    $html = '<section class="collapsed bg-none"><div class="row"><div class="layout"><div class= "full-width"><divclass="text">';

                    $html .= "<div data-alert class=\"alert-box $type radius\">";
                    $html .= $message;
                    $html .= "<a href=\"#\" class=\"close\">&times;</a>";
                    $html .= "</div></div></div></div></div></section>";
    return $html;

    });

        ?>

    <section class="extra-space bg-none section" id="content">

        <div class="row">
            <div class="layout">
                <h2>Review# {{$model->signatureid}}</h2>
                <div class="full-width">
                    <div id='loadingmessage'>

                    </div>


                    <form class="filter">



                        <div class="row">
                            <div class="small-3 columns">
                                <label for="review comments">Comments</label>

                            </div>
                            <div class="small-9 columns">
                            <textarea name="comment" placeholder="enter review comments"></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="signatureid" value="{{$model->signatureid}}"/>

                        <div class="row">
                            <div class="small-9 columns right">
                        <ul class="button-group radius">
                            @foreach($model->statuses as $item)
                                <?php $action_name = strtolower($item->action);?>
                                <li><a href="{{url("/signatures/$action_name")}}" class="medium button"
                                            >{{$item->buttonText}}</a></li>
                            @endforeach
                            <li><a href="{{url("/signatures")}}" class="button">Cancel</a></li>
                        </ul>

                            </div>
                            </div>


                   </form>

                    <div id="example-images">
                        {!! $model->signature->getSignaturePreview() !!}
                    </div>

                </div>
        </div></div>
    </section>
    @endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('form .button').on('click',function(event){
                event.preventDefault();
                    $('html,body').animate({
                        scrollTop: 128
                    }, 1000);

                var href = $(this).attr('href');
                $('#loadingmessage').addClass('loading');
                $.get(href,$(this).parents('form').serialize(),function(data){
                    var result= JSON.parse(data);
                    $('#loadingmessage').removeClass('loading');
                    if(result.status){
                        window.location.replace("{{url("/signatures?message=Successfully completed the process")}}");
                        return;
                    }
                    else
                    {
                        $('.filter').prepend('{!! \HTML::flash("Error occurred during the process",
                        \App\Models\ViewModels\Alerts::ALERT) !!}');
                        return;
                    }

                });

                // close alert box -
                $('.alert-box > a.close').click(function() { $(this).closest('[data-alert]').fadeOut(); });

            });
        })
    </script>

 @endsection
