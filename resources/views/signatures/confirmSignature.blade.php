@extends('app')

@section('content')

    <section class="section bg-none profile detail" id="content">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                    {!! Form::open(array('url' => '/savesignature','id'=>"svgform",'method'=>'post')) !!}

                    <div class="grid right">
                        {!! $backLink['backLink'] !!}
                        <input type="submit" id="saveSignature" name="saveSignature"
                               value="Submit for Approval" class="button">
                    </div>

                    {!!  Form::hidden('named', $model->named) !!}
                    {!!  Form::hidden('p', $model->primaryText) !!}
                    {!!  Form::hidden('s', $model->secondaryText) !!}
                    {!!  Form::hidden('t', $model->tertiaryText) !!}


                    {!!  Form::hidden('campus', $model->campus) !!}



                    @if($model->signatureid)
                        <input type="hidden" name="signatureid" value="{{$model->signatureid }}">
                    @endif
                    <dl class="meta inline">
                        <dt>Campus:</dt> <dd itemprop="campus">{{$model->campus}}</dd>

                        @if($model->named==1)

                            <dt>Named School:</dt> <dd itemprop="namedschool">Yes</dd>
                            <dt>Primary Text: </dt>
                            <dd itemprop="primarytext">{{$model->primaryText}}</dd>
                            <dt>Secondary Text: </dt><dd itemprop="secondaryText">{{$model->secondaryText}}</dd>
                            @if(isset($model->tertiaryText) && $model->tertiaryText!="")
                                <dt>Tertiary Text: </dt><dd itemprop="tertiaryText">{{$model->tertiaryText}}</dd>
                            @endif

                        @else

                            <dt>Named School:</dt> <dd itemprop="nonnamedschool">No</dd>
                            <dt>Secondary Text: </dt><dd itemprop="secondaryText">{{$model->secondaryText}}</dd>
                            <dt>Primary Text: </dt>
                            <dd itemprop="primarytext">{{$model->primaryText}}</dd>
                            @if(isset($model->tertiaryText) && $model->tertiaryText!="")
                                <dt>Tertiary Text: </dt><dd itemprop="tertiaryText">{{$model->tertiaryText}}</dd>
                            @endif


                        @endif

                    </dl>
                    <div class="text">
                        <h2>Preview</h2>
                        <!-- signature preview -->
                        {!!$model->getSignaturePreview()!!}
                    </div>



                    <div class="grid right">
                        {!! $backLink['backLink'] !!}
                        <input type="submit" id="saveSignature" name="saveSignature"
                               value="Submit for Approval" class="button">
                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </section>

@endsection
