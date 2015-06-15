<!-- Navigation - move to navigation  -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/home') }}">Brand Admin</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/signatures') }}">Manage Signatures</a></li>
					@if($navigation['isAdmin'])
                    <li><a href="{{ url('/users') }}">Manage Users</a></li>
                    @endif
				</ul>
				 <div class="pull-right" id="loginUserControl">

                </div>

			</div>



		</div>
	</nav>


