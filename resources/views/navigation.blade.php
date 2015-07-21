


<nav role="navigation" id="nav-main" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement"
	 class="main hide-for-medium-down show-for-large-up dropdown">
	<ul class="row pad">
		<li class="show-on-sticky home">
			<a href="{{ url('/') }}">Home</a>
		</li>

		@if($navigation['isAdmin'])
			  <li class="first"><a  href="{{ url('/signatures') }}" itemprop="url" class="current">
					<span itemprop="name">Manage Signatures</span></a>
			  </li>
			<li><a href="{{ url('/admin') }}"><span itemprop="name">Manage Administrators</span></a></li>

	    @else
			<li class="last"><a  href="{{ url('/signatures') }}" itemprop="url" class="current">
					<span itemprop="name">Manage Signatures</span></a></li>
		@endif


		</ul>
	</nav>