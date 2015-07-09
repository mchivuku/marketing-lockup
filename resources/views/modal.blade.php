<?php


$html = array();
$attributes = $model->getAttributes();
$render_html=  \App\Models\ViewModels\BootstrapSettings::renderAttributes($attributes);
?>


<div class="modal fade" {{$render_html}} tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">{{$model->title}}</h4>
      </div>
      <div class="modal-body">

        {!!$model->content !!}

      </div>
    </div>
  </div>
</div>
