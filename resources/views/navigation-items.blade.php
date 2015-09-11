<li class="show-on-sticky home">
    <a href="{{ url('/') }}">Home</a>
</li>


<?php
$anchor_link = function($path,$text,$children=false){
    $current = Route::getCurrentRoute()->getPath();

    $href = url($path);

    if($current===$path && $children==false){
        return "<a class='current'  href=\"$href\" itemprop=\"url\"><span
					itemprop=\"name\">$text</span></a>";
    }
    return "<a  href=\"$href\" itemprop=\"url\"><span itemprop=\"name\">$text</span></a>";

}
?>

<li  class="first">{!!$anchor_link('signatures/create','Create Marketing Lock-up')!!}</li>
<li>{!!$anchor_link('signatures','Manage Marketing Lock-ups')!!}</li>

<li>{!!$anchor_link('emaillockup','Create Email Signature')!!}</li>

@if($navigation['isAdmin'])

    <li class="last">{!!$anchor_link('admin','Manage Administrators')!!}
    </li>

@endif
