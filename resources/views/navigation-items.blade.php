<li class="show-on-sticky home">
    <a href="{{ url('/') }}">Home</a>
</li>


<?php
$anchor_link = function($path,$text,$children=false){
    $current = Route::getCurrentRoute()->getPath();

    $href = url($path);

    if(stripos($current,$path)!==false && $children==false){
        return "<a class='current'  href=\"$href\" itemprop=\"url\"><span
					itemprop=\"name\">$text</span></a>";
    }
    return "<a  href=\"$href\" itemprop=\"url\"><span itemprop=\"name\">$text</span></a>";

}
?>

<li class="first">
    {!!$anchor_link('signatures','Manage Signatures')!!}
    <ul class="children">
        <li>{!!$anchor_link('signatures/create','Create Signature',true)!!}</li>
    </ul>

</li>

<li>
    {!!$anchor_link('emaillockup','Email Lockup')!!}


</li>

@if($navigation['isAdmin'])

    <li>{!!$anchor_link('admin','Manage Administrators')!!}
    </li>

@endif