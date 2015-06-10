@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Users List</div>

                    <div class="panel-body">
                        <p>Table with a list of users will be shown here.</p>
                        <?php
                            $itemsCollection = ($dt["usersCollection"]);
                            $usersArray = $itemsCollection -> toArray();
                        ?>
                        <table class="table" id="usersTable">
                           <thead>
                            <tr>
                                <th>
                                    Username
                                </th>
                                <th>
                                    Email Address
                                </th>
                            </tr>
                           </thead>
                            <tbody>
                                <?php
                                    $itemsCollection -> each(function($user){
                                ?>
                                <tr>
                                    <td>
                                        {{{$user -> username}}}
                                    </td>
                                    <td>
                                        {{{$user -> email}}}
                                    </td>
                                </tr>
                                <?php
                                    });
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bodyScripts')
    <script>
        $(document).ready(function() {
            $('#usersTable').dataTable();
        } );
    </script>
@endsection
