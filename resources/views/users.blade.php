@extends('app')

@section('content')
<div class="row">
<div class="col-lg-10">
 <a href="{{url("/users/search")}}" class="<?php echo \App\Models\BootstrapSettings::$primaryButtonClass;?>
 pull-right" data-toggle="modal"  data-target="#userSearchModal">
 Add New User
 </a>
</div>

</div>

<div class="row">
<div class="col-lg-10">


  <p>Table with a list of users will be shown here.</p>
                        <?php
                            $itemsCollection = ($model);
                            $usersArray = $itemsCollection -> toArray();
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
                                  <th>
                                      Role
                                  </th>
                                  <th></th>
                            </tr>
                           </thead>
                            <tbody>
                                <?php
                                    $itemsCollection -> each(function($user){
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
                                                                         <td>
                                                                                                                                                    {{{$user -> role->name}}}

                                      <td>  <a href="{{ url('/users/get?username='.$user['username']) }}"
                                                                               data-toggle="modal"
                                                                               data-target="#viewModel">View</a>&nbsp;|&nbsp;

                                                                            <a href="{{ url('/users/edit?username='.$user['username'])
                                                                             }}"  data-toggle="modal" data-target="#editModal">Edit</a>
                                                                                                                                                                                                                               </td>
                                </tr>
                                <?php
                                    });
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
