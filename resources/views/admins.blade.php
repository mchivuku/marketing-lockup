@extends('app')

@section('content')
<div class="row">
<div class="col-lg-10">
 <a href="{{url("/admin/search")}}" class="<?php echo \App\Models\BootstrapSettings::$primaryButtonClass;?>
 pull-right" data-toggle="modal"  data-target="#userSearchModal">
 Add New Administrator
 </a>
</div>

</div>

<div class="row">
<div class="col-lg-10">


  <p>Table with a list of users will be shown here.</p>
<?php
$table = new StdClass;
    $table->header = array('First Name','Last Name','Email Address','&nbsp;');
    $table->data = array_map(function($item){

        $view_link = "<a href='admin/get?username=".$item->username."' data-toggle=\"modal\"
        data-target=\"#viewModel\">View</a>";
        $delete_link = "<a href='admin/delete?username=".$item->username."' data-toggle=\"modal\"
        data-target=\"#viewModel\">Delete</a>";
        return
                array($item->firstName,$item->lastName,$item->email,$view_link." | ".$delete_link);},$model);

    $table->attributes = array('class'=>'table','id'=>'usersTable');

    ?>

  @include('table',
  array('model'=>$table
  ))


@endsection
@section('bodyScripts')

     <script>
        $(document).ready(function() {

            $('#usersTable').dataTable();

$('[data-toggle="modal"]').click(function(e) {
  e.preventDefault();
  var url = $(this).attr('href');
  //var modal_id = $(this).attr('data-target');
  $.get(url, function(data) {
      $(data).modal();
  });
});


});

    </script>

@endsection
