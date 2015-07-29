@if(\Session::has('flash-message'))

    <?php
    $flash_message = \Session::get('flash-message');
    $type = $flash_message->type;
    $message = $flash_message->message;

    ?>

    <section class="collapsed bg-none">
        <div class="row">
            <div class="layout">
                <div class="full-width">
                    <div class="text">
                        <div data-alert class="alert-box {{$type}} radius">
                            {{$message}}
                            <a href="#" class="close">&times;</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endif
