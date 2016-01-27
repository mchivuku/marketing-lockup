<?php

$table = new StdClass;

$table->header = array('Preview','Name','Status','Date','Review Comments','Action','Download');

 $model->signatures->each(function ($item)use($table){
    $format_date_time = function($date){
        return  "<span class=\"hideText\">".$date."</span>".date("F j, Y",strtotime($date));
    };

  // $preview = $item->getSignatureThumbnail();
   //  $preview = 'Preview';

   // $preview_link = "<a  data-reveal-id=\"viewModal\"   href='signatures/getPreview?id=".$item->signatureid."'
    //class=\"modal\">Preview</a><span class=\"hideText\">".$item->primaryText." " .$item->secondaryText." " .$item->tertiaryText."</span>";

     $text="<span class=\"hideText\">";
     if($item->primaryText!="")
         $text.=trim($item->primaryText);
     if($item->secondaryText!="")
         $text.=trim($item->secondaryText);
     if($item->tertiaryText!="")
          $text.=trim($item->tertiaryText);
     $text.="</span>";

     $preview_link = "<a  data-reveal-id=\"viewModal\"   href='signatures/getPreview?id=".$item->signatureid."'
                      class=\"modal\">Preview</a>";


     if($item->downloadPath!=''){
         $download_link = "<a href='signatures/download?signatureid=".$item->signatureid."'>Download</a>";
     }else{
         $download_link="&nbsp;";
     }

     //comments link
     $comments_link = '';
     if($item->comments!=""){
         $comments_link = "<a data-reveal-id=\"viewModal\" href='signatures/getReviewComments?signatureid=".$item->signatureid."'
    class=\"modal\">View</a>".$text;

     }else{
         $comments_link=''.$text;
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

    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.tab.js")}}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('/js/dataTables.foundation.js') }}"></script>
    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.reveal.js")}}"></script>
    <script type="text/javascript" src="{{asset("js/tablesaw.stackonly.js")}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#signatureTable').dataTable({ "order": [[ 4, "desc" ]], "ordering": false,"info": false,"aoColumns" : [
                {'bSortable' : false},
                {"sType" : "string"},
                {"sType" : "string"},
                {"sType" : "string"},
                {"sType" : "string"},
                {"sType" : "string"},
                {"sType" : "string"}
            ]        });

        });

    </script>
@endsection

