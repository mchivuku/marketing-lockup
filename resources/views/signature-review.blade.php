@extends('signatureslayout')
@section('content')



    <section class="section page-title bg-none">
        <div class="row"><div class="layout">
                <h1><span>Signature Summary - </span> {{$model->content->signatureid}}</h1>
            </div>
        </div>
    </section>


    <section class="extra-space bg-none section" id="content">
        <div class="row">
            <div class="layout">
                <div class="full-width">
                    <form>

                        <div class="row">
                            <div class="small-3 columns">
                                <label for="Preview" class="right">Preview:</label>
                            </div>
                            <div class="small-9 columns">
                                {!! $model->content->getSignaturePreview() !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="small-3 columns">
                                <label for="Created Date" class="right">Created Date:</label>
                            </div>
                            <div class="small-9 columns">
                               {{$model->content->created_at}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="small-3 columns">
                                <label for="Status" class="right">Status:</label>
                            </div>
                            <div class="small-9 columns">
                             {{$model->content->reviewStatus->status}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="small-3 columns">
                                <label for="Created Date" class="right">Created By:</label>
                            </div>
                            <div class="small-9 columns">
                                {{$model->content->username}}
                            </div>
                        </div>


                        <div class="row">
                            <div class="small-3 columns">
                                <label for="Updated Date" class="right">Updated Date:</label>
                            </div>
                            <div class="small-9 columns"> &nbsp;
                            </div>
                        </div>

                        <div class="row">
                            <div class="small-3 columns">
                                <label for="Updated Date" class="right">Updated Date:</label>
                            </div>
                            <div class="small-9 columns">

                             </div>
                        </div>

                        <div class="row">
                            <div class="small-3 columns">
                                <label for="review comments" class="right">Review Comments:</label>
                            </div>
                            <div class="small-9 columns">
                                {{$model->content->signaturereviews()->where(func_get_arg())}}
                            </div>
                        </div>

            </form>
                </div>
        </div></div>
    </section>
    @endsection