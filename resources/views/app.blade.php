@include('...includes.header')
<body>
@include('...includes.branding-bar')
	<div class="inner-wrap">
		<header>
			<div class="row pad">

				<h1><a href="{{url("/")}}">Marketing Lock-up</a></h1>

			</div>
		</header>


		<!-- Navigation -->
		@include('navigation')

   		 <main  style="min-height: 84px;">

			 @yield('left-navigation')


			 <!-- Page Title -->
			 <section class="section page-title bg-none">
				 <div class="row">
					 <div  class="layout">
						 <h1>{{$title}}</h1>
					 </div>
				 </div>
			 </section>


			 @include('...includes.alerts')

             @yield('content')


           <!-- Footer -->
			@include('...includes.footer')


		</main>

	</div>

	<div class="right-off-canvas-menu show-for-medium-down">
		<nav class="mobile off-canvas-list">
				@include('navigation',array('navigation'=>$navigation))
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
    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.alert.js")
 }}"></script>

<script src="{{asset("js/svg.js")}}" type="text/javascript"></script>

@yield('scripts')


</body>
</html>

