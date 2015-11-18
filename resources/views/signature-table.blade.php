<?php

$table = new StdClass;

$table->header = array('Preview','Name','Status','Date','Review Comments','Action','Download');

 $model->signatures->each(function ($item)use($table){
    $format_date_time = function($date){
        return  date("F j, Y",strtotime($date));
    };


   //$preview = $item->getSignatureThumbnail();
   //  $preview = 'Preview';

    $preview_link = "<a  data-reveal-id=\"viewModal\"   href='signatures/getPreview?id=".$item->signatureid."'
    class=\"modal tiptext\">Preview <div class='thumbnail' data-attribute-id='".$item->signatureid."'></div></a>";

     if($item->downloadPath!=''){
         $download_link = "<a href='signatures/download?signatureid=".$item->signatureid."'>Download</a>";
     }else{
         $download_link="&nbsp;";
     }

     //comments link
     $comments_link = '';
     if($item->comments!=""){
         $comments_link = "<a data-reveal-id=\"viewModal\" href='signatures/getReviewComments?signatureid=".$item->signatureid."'
    class=\"modal\">View</a>";

     }else{
         $comments_link='';
     }


     $table->data[] = array(
             $preview_link,$item->name,
             $item->reviewstatus->status,
             $format_date_time($item->updated_at),
             $comments_link,
             $item->nextAction,
             $download_link
    );

});
$table->attributes = array('class'=>'tablesaw table','id'=>'signatureTable','data-tablesaw-mode'
=>"stack");
?>


@if(count($model->signatures)==0)
        <p>No records were found.</p>
@else

         @include('table',array('model'=>$table))
        <div id="viewModal" class="reveal-modal" data-reveal></div>
@endif


@section("scripts")

    <script type="text/javascript">

        $(document).ready(function() {
            $('#signatureTable').dataTable({  "ordering": false,"info": false});
        });

        $(".tiptext").mouseover(function() {
            var element = $(this).children('.thumbnail');
            var id = element.attr('data-attribute-id');
            $.get('signatures/getThumbnail?id='+id,null,function(data){
                element.empty().html(data);
            });
            element.show();
        }).mouseout(function() {
            $(this).children(".thumbnail").hide();
        });
    </script>
@endsection

