
<div class="toggleElements">
    @if(!isset($named))
        @include("includes.named-school-form",
   array('primaryText'=>$primaryText,'secondaryText'=>$secondaryText,
  'tertiaryText'=>$tertiaryText))
    @else
        @if($named==1)
            @include("includes.named-school-form",
       array('primaryText'=>$primaryText,'secondaryText'=>$secondaryText,
      'tertiaryText'=>$tertiaryText))
        @else
            @include("includes.non-named-school-form",
       array('primaryText'=>$primaryText,'secondaryText'=>$secondaryText,
       'tertiaryText'=>$tertiaryText))
        @endif

    @endif

</div>