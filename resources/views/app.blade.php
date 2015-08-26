@include('...includes.header')
<body id="home" class="">
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
				<h1><a href="{{url("/")}}">Marketing <span>Lock-up</span></a></h1>
			</div>
		</header>

		<!-- Navigation -->
		@include('navigation')


   		 <main>

			 <!-- Left navigation -->
			 @yield('left-navigation')


			 <!-- Page Title -->
			 <section class="section page-title bg-none"><div class="row"><div class="layout"> <h1>{{$title}}</h1></div></div></section>

			 @include('...includes.alerts')

             @yield('content')


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
	<script src="{{asset("assets/bower_components/foundation/js/vendor/jquery.js")}}" type="text/javascript"></script>
	<script src="//assets.iu.edu/web/2.x/js/global.js" type="text/javascript"></script>
	<script src="//assets.iu.edu/search/2.x/search.js" type="text/javascript"></script>
	<script type="text/javascript" language="javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="{{ asset('/js/dataTables.foundation.js') }}"></script>
	<script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.js")}}"></script>
	<script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.reveal.js")}}"></script>
    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.alert.js")}}"></script>
	<script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.offcanvas.js")}}"></script>

	<script type="text/javascript" src="{{asset("bower_components/jquery-validation/dist/jquery.validate.min.js")}}"></script>

	<script type="text/javascript" src="{{asset("js/tablesaw.stackonly.js")}}"></script>

	<script src="{{asset("js/svg.js")}}" type="text/javascript"></script>

</div>
@yield('scripts')
</body>
</html>

