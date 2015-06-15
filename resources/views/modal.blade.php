<?php
$html = array();
$attributes = $model->getAttributes();

$attributeElement = function($key,$value){
if (is_numeric($key)) $key = $value;

		if ( ! is_null($value)) return $key.'="'.e($value).'"';

};

foreach ((array) $attributes as $key => $value)
		{
			$element = $attributeElement($key, $value);

			if ( ! is_null($element)) $html[] = $element;
		}

$render_html= count($html) > 0 ? ' '.implode(' ', $html) : '';
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
      <!--div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div-->
    </div>
  </div>
</div>
