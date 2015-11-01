@extends('signatureslayout')

@section('content')

    <section class="section bg-none profile detail" id="content">
        <div class="row">
            <div class="layout">

                <div class="full-width">

                    {!! Form::open(array('url' => '/signatures/savesignature','id'=>"svgform",'method'=>'post')) !!}

                    {!!  Form::hidden('named', $model->named) !!}
                    {!!  Form::hidden('p', $model->primaryText) !!}
                    {!!  Form::hidden('s', $model->secondaryText) !!}
                    {!!  Form::hidden('t', $model->tertiaryText) !!}

                    @if($model->signatureid)
                        <input type="hidden" name="signatureid" value="{{$model->signatureid }}">
                    @endif

                    @if($model->named==1)
                    <dl class="meta inline">
                        <dt>Named School:</dt> <dd itemprop="namedschool">Yes</dd>
                        <dt>Primary Text: </dt>
                        <dd itemprop="primarytext">{{$model->primaryText}}</dd>
                        <dt>Secondary Text: </dt><dd itemprop="secondaryText">{{$model->secondaryText}}</dd>
                        @if(isset($model->tertiaryText) && $model->tertiaryText!="")
                            <dt>Tertiary Text: </dt><dd itemprop="tertiaryText">{{$model->tertiaryText}}</dd>
                       @endif
                    </dl>
                    @else

                        <dl class="meta inline">
                            <dt>Named School:</dt> <dd itemprop="nonnamedschool">No</dd>
                            <dt>Secondary Text: </dt><dd itemprop="secondaryText">{{$model->secondaryText}}</dd>
                            <dt>Primary Text: </dt>
                            <dd itemprop="primarytext">{{$model->primaryText}}</dd>
                            @if(isset($model->tertiaryText) && $model->tertiaryText!="")
                                <dt>Tertiary Text: </dt><dd itemprop="tertiaryText">{{$model->tertiaryText}}</dd>
                            @endif
                        </dl>

                    @endif

                   <div class="text">
                       <h2>Preview</h2>
                       <!-- signature preview -->
                       {!!$model->getSignaturePreview()!!}
                   </div>


                    <div class="button-group right">
                        {!! $backLink['backLink'] !!}
                        <input type="submit" id="saveSignature" name="saveSignature"
                               value="Submit for Approval" class="small button">

                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </section>

@endsection
