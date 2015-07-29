<nav role="navigation" id="nav-main" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement"
	 class="main hide-for-medium-down show-for-large-up dropdown">
	<ul class="row pad">
		<li class="show-on-sticky home">
			<a href="{{ url('/') }}">Home</a>
		</li>


		<?php
			$anchor_link = function($path,$text){
				$current = Route::getCurrentRoute()->getPath();

				$href = url($path);

				if(stripos($current,$path)!==false){
					return "<a class='current'  href=\"$href\" itemprop=\"url\"><span
					itemprop=\"name\">$text</span></a>";
				}
				return "<a  href=\"$href\" itemprop=\"url\"><span itemprop=\"name\">$text</span></a>";

			}
		?>

	@if($navigation['isAdmin'])
			  <li class="first">
				  {!!$anchor_link('signatures','Manage Signatures')  !!}
				  <ul class="children">
					  <li>{!!$anchor_link( 'signatures/create','Create Signature')!!}</li>
				  </ul>

			  </li>
			<li>{!!$anchor_link('admin','Manage Administrators')!!}
			</li>

	    @else
			<li class="first">
				{!!$anchor_link('signatures','Manage Signatures')!!}
			<ul class="children">
  			  <li>{!!$anchor_link('signatures/create','Create Signature')!!}</li>
			</ul>

			</li>
@endif


</ul>
</nav>