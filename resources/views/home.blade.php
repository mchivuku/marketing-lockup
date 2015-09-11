@extends('app')

@section('banner')

    <section role="banner" id="banner" class="bg-secondary dark text-overlay section"><div class="row pad"><div
                    class="full-width"><h2>Build your custom lock-up</h2>

                <p>Marketing lock-ups help schools, departments, and other units stay consistent to the Indiana University brand.</p>
                <p>While official IU signatures are used for letterheads, envelopes, business cards, and internal documents, lock-ups are used for all marketing channels—digital, print, web, and virtually everything else.</p>

            </div></div></section>



@endsection

@section('content')

    <section id="content" class="collapsed bg-none section">
        <div class="row">
            <div class="layout">
                <div class="full-width">
                    <div class="text">

                       <h4>This generator takes the guesswork out of building your unit’s marketing lock-up.</h4>
                        <p>You
                            can
                            <a  href={{url('signatures/create')}} itemprop="url">design your lock-up</a> using primary, secondary, and
                            tertiary naming systems. You also can <a  href={{url('emaillockup')}} itemprop="url">design a custom email lock-up</a> that reproduces beautifully in the digital space.</p>
                        <p>The lock-up generator uses variations in font weights to best display your unit’s name, based on the information you determine is primary, secondary, and tertiary. It also protects the space around the trident, and it produces a graphic that is optimized for both print and digital communications.</p>



                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="collapsed bg-none section" id="homepage-feature">
        <div class="row">
            <div class="layout">

                    <div class="grid halves">
                        <div class="feature">
                            <figure itemtype="http://schema.org/ImageObject" itemscope="itemscope" class="media image">
                                <img src="{{asset("/img/AADSLockup.png")}}" itemprop="contentUrl"
                                     alt="african american and african diaspora studies"></figure>

                        </div>
                        <div class="feature">
                            <figure itemtype="http://schema.org/ImageObject" itemscope="itemscope" class="media image">
                                <img src="{{asset("/img/kelley.png")}}" itemprop="contentUrl"
                                     alt="kelley"></figure>
                        </div>

    </div></div></div></section>


@endsection