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
                            $itemsCollection = ($model);

                        ?>
                        <table class="table" id="usersTable">
                           <thead>
                            <tr>
                                <th>
                                    First Name
                                </th>
                                <th>
                                    Last Name
                                </th>
                                 <th>
                                	 Email Address
                                 </th>

                                  <th></th>
                            </tr>
                           </thead>
                            <tbody>
                                <?php
                                    foreach($model as $user){
                                ?>
                                <tr>
                                    <td>
                                        {{{$user -> firstName}}}
                                    </td>
                                    <td>
                                        {{{$user -> lastName}}}
                                    </td>
                                    <td>
                                                                            {{{$user -> email}}}
                                                                        </td>


                                      <td>  <a href="{{ url('/admin/get?username='.$user->username) }}"
                                                                               data-toggle="modal"
                                                                               data-target="#viewModel">View</a>

 									 </td>
                                                                                                                                                              </td>
                                </tr>
                                <?php
                                    };
                                ?>
                            </tbody>
                   </table>


</div>

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
