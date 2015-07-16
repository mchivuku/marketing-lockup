@extends('signatureslayout')

@section('content')

<meta name="_token" content="{
    { csrf_token() }}" />

<div class="row">
<div class="col-lg-10">

    <?php
    $table = new StdClass;
    $table->header = array('Name','Status','Comments', 'Created Time','Updated Time' ,
            'Preview','Actions');
    $table->data = array_map(function($item){

        $preview_link = "<a href='signatures/getPreview?id=".$item->id."' data-toggle=\"modal\"
        data-target=\"#viewModel\">Preview</a>";
        return  array($item->name,$item->status,
                        $item->comments,
                        $item->created_at,
                        $item->updated_at, $preview_link,'');},
            $model);

    $table->attributes = array('class'=>'table','id'=>'signatureTable');

    ?>

        @include('table',
    array('model'=>$table
    ))


</div>
</div>

@endsection


