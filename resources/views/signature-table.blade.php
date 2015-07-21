<?php

$table = new StdClass;

$table->header = array('Preview','Name','Status','Date','Review Comments','Action','Download');

 $model->signatures->each(function ($item)use($table){
    $format_date_time = function($date){
        return  date("F j, Y",strtotime($date));
    };

    $preview_link = "<a data-reveal-id=\"viewModal\" href='signatures/getPreview?id=".$item->signatureid."'
    class=\"modal\">$item->preview</a>";

     $table->data[] = array($preview_link,$item->name,
            $item->reviewstatus->status,
            $format_date_time($item->updated_at),
            $item->comments,
            $item->nextAction,
            '&nbsp;'

    );

});

$table->attributes = array('class'=>'table','id'=>'signatureTable');
?>
<div class="panel">

@if(count($model->signatures)==0)
        <p>No records were found!</p>

@else
    @include('table',array('model'=>$table))
   <div id="viewModal" class="reveal-modal" data-reveal></div>

@endif
</div>
