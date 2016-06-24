@extends('app')
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
                                        <li><a href="{{url("$action_name")}}" class="medium button"
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


    <!-- SPINNER MODAL -->
    <div class="reveal-modal medium" id="spinner-modal" style="display:none;height:200px;" data-reveal>
        <div class="center" style="position: absolute;width:200px; height:200px;top: 40%;
    left: 40%;margin-left: -30px; margin-top: -25px;"><h3>Please wait...</h3>
            <p class="loading" style="background-size:40px;
    height:100px;width:200px;"></p></div>

    </div>


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

                $('#spinner-modal').foundation('reveal', 'open')
                $.get(href,$(this).parents('form').serialize(),function(data){
                    $('#spinner-modal').hide();
                    var result= JSON.parse(data);
                    if(result.status){
                        window.location.replace("{{url("/manage.html?message=Successfully completed the process")}}");
                        return;
                    }
                    else
                    {
                        $('#spinner-modal').foundation('reveal', 'close')
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
