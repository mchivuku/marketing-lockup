<!DOCTYPE html>
<html lang="en">
<head prefix="og: http://ogp.me/ns# profile: http://ogp.me/ns/profile# article: http://ogp.me/ns/article#">
	<meta charset="utf-8"/>
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<title>{{$title}}</title>

	<meta content="iu brand, iu brand guidelines" name="keywords"/>
	<meta content="Learn how to use the IU brand in all of your communications and marketing materials. "
		  name="description"/>

	<meta content="IE=edge" http-equiv="X-UA-Compatible"/>
	<link href="https://assets.iu.edu/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
	<link href="https://brand.iu.edu/index.html" itemprop="url" rel="canonical"/>
	<meta content="https://brand.iu.edu/images/homepagebanner-brandsite.jpg" property="og:image"/>
	<meta content="IU Brand Guidelines" property="og:title"/>
	<meta content="Learn how to use the IU brand in all of your communications and marketing materials. "
		  property="og:description"/>
	<meta content="website" property="og:type"/>
	<meta content="https://brand.iu.edu/index.html" property="og:url"/>
	<meta content="IU Brand Guidelines" property="og:site_name"/>
	<meta content="en_US" property="og:locale"/>

	<meta content="https://brand.iu.edu/images/homepagebanner-brandsite.jpg" name="twitter:image:src"/>
	<meta content="IU Brand Guidelines" name="twitter:title"/>

	<meta content="summary_large_image" name="twitter:card"/>

	<meta content="https://brand.iu.edu/images/homepagebanner-brandsite.jpg" itemprop="image"/>
	<meta content="IU Brand Guidelines" itemprop="name"/>
	<meta content="Learn how to use the IU brand in all of your communications and marketing materials. "
		  itemprop="description"/>

	<link href="{{asset("css/foundation.min.css")}}" rel="stylesheet">

	<?php $_GET['path'] = 'css.html';include("includer.php");?>

	<link href="{{asset("css/app.min.css")}}" rel="stylesheet">
	<link href="{{asset("css/grid.css")}}" rel="stylesheet">


	<!-- Javascript Head -->
	<?php $_GET['path'] = 'javascript-head.html';include("includer.php");?>

			<!-- Tag manager -->
	<?php $_GET['path'] = 'analytics.html';include("includer.php");?>

</head>

<body class="mahogany">

<?php $_GET['path'] = 'tag-manager.html';include("includer.php");?>

<?php $_GET['path'] = 'skipnav.html';include("includer.php");?>

<div class="off-canvas-wrap" data-offcanvas="">


	<?php include "gwassets/brand/2.x/header-iu.html"; ?>
	<?php include "gwassets/search/2.x/search.html"; ?>


	<div class="inner-wrap">
		<?php $_GET['path'] = 'header.html';include("includer.php");?>

				<!-- Section Navigation -->
		@include('...includes.navigation-primary')


		@yield('banner')

		<main>

			<!-- Left navigation -->
			@include('...includes.navigation-section')


					<!-- Page Title -->
			@if(isset($pageTitle))
				<section class="section page-title bg-none">
					<div class="row">
						<div class="layout">
							<h1>{{$pageTitle}}</h1></div>
					</div>
				</section>
				@endif

				@include('...includes.alerts')

				@yield('content')



						<!-- Footer -->
				<?php include "gwassets/brand/2.x/footer.html"; ?>


		</main>

	</div>

	@include('...includes.navigation-mobile')

			<!-- Scripts -->
	<?php $_GET['path'] = 'javascript.html';include("includer.php");?>

	<script src="{{asset("js/app.min.js")}}" type="text/javascript"></script>


</div>
@yield('scripts')
</body>
</html>

