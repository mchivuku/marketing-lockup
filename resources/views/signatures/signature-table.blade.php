<?php

$table = new StdClass;

$table->header = array('Preview','Name','Status','Date','Review Comments','Action','Download');

$model->signatures->each(function ($item)use($table){
    $format_date_time = function($date){
        return  "<span class=\"hideText\">".$date."</span>".date("F j, Y",strtotime($date));
    };

    $preview_link = "<a  data-reveal-id=\"viewModal\"  href='".\URL::to("getPreview")."?id=".$item->signatureid."'
     class=\"modal tiptext\" onMouseOver=\"showThumbnail((this)); return true;\"
     onMouseOut=\"hideThumbnail((this)); return true;\">Preview<div  class='thumbnail'   data-attribute-id='".$item->signatureid."'></div></a>";

    // this is to allow searching on primary,secondary,tertiary text
    $text="<span class=\"hideText\">";
    if($item->primaryText!="")
        $text.=trim($item->primaryText);
    if($item->secondaryText!="")
        $text.=trim($item->secondaryText);
    if($item->tertiaryText!="")
        $text.=trim($item->tertiaryText);
    $text.="</span>";


    if($item->downloadPath!=''){

        $download_link = "<a href='".\URL::to("download")."?signatureid=".$item->signatureid."'>Download</a>";
    }else{
        $download_link="&nbsp;";
    }

    //comments link
    $comments_link = '';
    if($item->comments!=""){
        $comments_link = "<a data-reveal-id=\"viewModal\" href='".\URL::to("getReviewComments")."?signatureid=".$item->signatureid."'
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
$table->attributes = array('class'=>'table mobile-labels','id'=>'signatureTable');
?>


@if(count($model->signatures)==0)
    <p>No records were found.</p>
@else

    @include('table',array('model'=>$table))
    <div id="viewModal" class="reveal-modal" data-reveal></div>
@endif


@section("scripts")

    <script type="text/javascript" language="javascript" src="{{ asset('/js/table.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#signatureTable').dataTable({  "ordering": false,"info": false});

            // Disable hover affect on mobile
            if(!Modernizr.touch){
                $(".tiptext").mouseover(function() {
                    showThumbnail((this))
                }).mouseout(function() {
                    hideThumbnail(this)
                });
            }

        });

        function showThumbnail(tipTextElement){

            var element = $(tipTextElement).children('.thumbnail');
            var id = element.attr('data-attribute-id');

            $(element).css('background-color','#ffffff');
            $(element).css('opacity','0.6');
            $(element).css('border','0px');

            $.get('{{URL::to("getThumbnail")}}?id='+id,null,function(data){
                $(element).removeClass('loading');
                element.empty().html(data);
                $(element).css('width',$(data).find('svg').attr('width'));
                $(element).css('height',85);
                $(element).css('overflow','hidden');
                $(element).css('border','1px solid #ccc');
                $(element).css('background-color','white');
                $(element).css('opacity','1');



            });

            element.show();

        }

        function hideThumbnail(tipTextElement){
            $(tipTextElement).children(".thumbnail").hide();
        }

    </script>
@endsection

