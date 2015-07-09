@extends('signatureslayout')

@section('content')


<meta name="_token" content="{
    { csrf_token() }}" />

<div class="row">
<div class="col-lg-10">
<table class="{{\App\Models\BootstrapSettings::$tableClass}}" id="signaturesTable">
<thead>
<th>Username</th>
<th>Preview</th>
<th>Status</th>
<th>Comments</th>
<th>Actions</th>
</thead>
<tbody>
<?php
foreach($model as $signature){
?>
<tr>

 <th>@break</th>
</tr>

<?php
}

?>
</tbody>
</table>
</div>
</div>

@endsection


