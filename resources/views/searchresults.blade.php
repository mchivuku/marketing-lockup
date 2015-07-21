

			<h4>Search Results:</h4>
			<div class="full-width">
				<div class="text">

<table class="table-bordered table table-responsive table-striped table-hover">
<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Username</th>
			<th>Email</th>
			<th>&nbsp;</th></tr>

@foreach($model as $user)
<tr>

	<td>{{$user['firstName']}}</td>
	<td>{{$user['lastName']}}</td>
	<td>{{$user['username']}}</td>
	<td>{{$user['email']}}</td>
	<td> {!!$user['addLink'] !!}</td>

</tr>
@endforeach

	</table>

