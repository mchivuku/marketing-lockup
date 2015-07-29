@extends('signatureslayout')
@section('content')



    <section class="extra-space bg-none section" id="content">

        <div class="row">
            <div class="layout">
                <h2>Signature Review# {{$model->signatureid}}</h2>
                <div class="full-width">
                    <div id="example-images">
                        {!! $model->signature->getSignaturePreview() !!}
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
                                <li><a href="{{url("/signatures/$action_name")}}" class="medium button">{{$item->buttonText}}</a></li>
                            @endforeach
                            <li><a href="{{url("/signatures")}}" class="button">Cancel</a></li>
                        </ul>

                            </div>
                            </div>


                   </form>


                </div>
        </div></div>
    </section>
    @endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('form .button').on('click',function(event){
                event.preventDefault();
                var href = $(this).attr('href');
                $.get(href,$(this).parents('form').serialize(),function($data){
                    window.location.replace("{{url("/signatures")}}");
                });

            });
        })
    </script>

 @endsection
