@include('...includes.header')
<body id="home" class="mahogany">
<div id="skipnav">
	<ul>
		<li><a href="#content">Skip to Content</a></li>
		<li><a href="#nav-main">Skip to Main Navigation</a></li>
	</ul>
 	<hr>
</div>


<div class="off-canvas-wrap" data-offcanvas="">


@include('...includes.branding-bar')

	<div class="inner-wrap">
		<header>
			<div class="row pad">
				<h1><a href="{{url("/")}}">Marketing <span>Lockup</span></a></h1>
			</div>
		</header>

		<!-- Navigation -->
		@include('navigation')
		@yield('banner')

   		 <main>

			 <!-- Left navigation -->
			 @yield('left-navigation')


			 <!-- Page Title -->
			 @if(isset($pageTitle))
				 <section class="section page-title bg-none"><div class="row"><div class="layout">
				 <h1>{{$pageTitle}}</h1></div></div></section>
			 @endif

			 @include('...includes.alerts')

             @yield('content')


           <!-- Belt -->
			 @include('...includes.belt')
			<!-- Footer -->
			@include('...includes.footer')


		</main>

	</div>

	<div class="right-off-canvas-menu show-for-medium-down">
		<nav class="mobile off-canvas-list">
			<ul>
				@include('navigation-items')
			</ul>
		</nav>
	</div>

 
	<!-- Scripts -->
	<!-- javascript files -->
	<!-- Include jQuery -->
	<script src="{{asset("js/jquery.js")}}" type="text/javascript"></script>
	<script src="//assets.iu.edu/web/2.x/js/global.js" type="text/javascript"></script>
	<script src="//assets.iu.edu/search/2.x/search.js" type="text/javascript"></script>
	<script type="text/javascript" src="{{asset("bower_components/jquery-validation/dist/jquery.validate.min.js")}}"></script>
	<script src="{{asset("js/svg.js")}}" type="text/javascript"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			var IUComm = window.IUComm || {};
			IUComm.init && IUComm.init({
				debug: true,
				settings: {
					bigNav: true
				}
			});
		});
	</script>
</div>
@yield('scripts')
</body>
</html>

