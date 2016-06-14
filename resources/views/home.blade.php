@extends('app')

@section('banner')

    <section role="banner" id="banner" class="bg-secondary dark text-overlay section"><div class="row pad"><div
                    class="full-width"><h2>Build your custom lockup</h2>

                <p>Marketing lockups help schools, departments, and other units stay consistent to the Indiana University brand.</p>
                <p>While official IU signatures are used for letterheads, envelopes, business cards, and internal documents, lockups are used for all marketing channels—digital, print, web, and virtually everything else.</p>

            </div></div></section>



@endsection

@section('content')

    <section id="content" class="collapsed bg-none section">
        <div class="row">
            <div class="layout">
                <div class="full-width">
                    <div class="text">

                       <h4>This generator takes the guesswork out of building your unit’s marketing lockup.</h4>
                        <p>You
                            can
                            <a  href={{url('signatures/create')}} itemprop="url">design your lockup</a> using primary, secondary, and
                            tertiary naming systems. You also can <a  href={{url('emaillockup')}} itemprop="url">design a custom email lockup</a> that reproduces beautifully in the digital space.</p>
                        <p>The lockup generator uses variations in font weights to best display your unit’s name, based on the information you determine is primary, secondary, and tertiary. It also protects the space around the trident, and it produces a graphic that is optimized for digital communications. To optimize lockup for print, please follow the directions included in your download.</p>



                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="collapsed bg-none section" id="homepage-feature">
        <div class="row">
            <div class="layout">
                    <div class="grid thirds">

                        <div class="feature">
                            <span class="help-text">Department, school, or unit</span>

                            <figure itemtype="http://schema.org/ImageObject" itemscope="itemscope" class="media
                            image" style="margin-top: 15px;">
                                <img src="{{asset("/img/chemistry.png")}}" itemprop="contentUrl"
                                     alt="department of chemistry">
                            </figure>

                        </div>
                        <div class="feature">
                            <span class="help-text">Named school</span>
                            <figure itemtype="http://schema.org/ImageObject" itemscope="itemscope" class="media
                            image"  style="margin-top: 15px;">
                                <img src="{{asset("/img/kelley.png")}}" itemprop="contentUrl"
                                     alt="kelley">
                            </figure>
                        </div>

    </div></div></div></section>


@endsection