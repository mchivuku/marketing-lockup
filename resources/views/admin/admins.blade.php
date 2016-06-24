@extends('app')

@section('content')

    <section class="collapsed bg-none section" id="content">

        <div class="row">
            <div class="layout">
                <div class="full-width">
                    <div class="text">
                        <a data-reveal-id='viewModal'
                           href="{{url("/admin/administrators/search")}}"class="button modal">
                            Add New Administrator
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="collapsed bg-none section" id="content">
        <div class="row">
            <div class="layout">
                <div class="full-width">

                    <?php
                    $table = new StdClass;
                    $table->header = array('First Name','Last Name','Email Address','Action');
                    $table->data = array_map(function($item){

                        $view_link = "<a  class='modal' data-reveal-id='viewModal'
                            href='".\URL::to("admin/administrators/get")."?username="
                                .$item->username
                                ."'>View</a>";
                        $delete_link = "<a href='".\URL::to("admin/administrators/delete")."?username=".$item->username
                                ."'>Delete</a>";
                        return  array($item->firstName,
                                $item->lastName,$item->email,$view_link." | ".$delete_link);},$model);

                    $table->attributes = array('class'=>'table mobile-labels','id'=>'usersTable');

                    ?>

                    @include('table',array('model'=>$table))

                </div>
            </div>
        </div>
    </section>

    <div id="viewModal" class="reveal-modal" data-reveal></div>

@endsection
@section('scripts')

    <script type="text/javascript" language="javascript" src="{{ asset('/js/table.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#usersTable').dataTable({  "ordering": true,"info": false});
        });

    </script>

@endsection
