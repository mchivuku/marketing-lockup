@extends('app')

@section('content')
    <section class="section page-title bg-none"><div class="row"><div
                    class="layout">
                <h1>Manage Administrators</h1></div></div>
    </section>

    <section class="collapsed bg-none section" id="content">
        <div class="row">
            <div class="layout">
                <div class="full-width">
                    <div class="text">

                        <p>  Table with a list of users will be shown here. </p>
                            <a data-reveal-id='viewModal' href="{{url("/admin/search")}}"class="button modal right">
                                Add New Administrator
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </section>

 <section class="collapsed bg-none" id="content">
     <div class="row">
         <div class="layout">
             <div class="full-width">
                <div class="text">
                    <?php
                        $table = new StdClass;
                        $table->header = array('First Name','Last Name','Email Address','&nbsp;');
                        $table->data = array_map(function($item){

                            $view_link = "<a  class='modal' data-reveal-id='viewModal' href='admin/get?username="
                                    .$item->username
                                    ."'>View</a>";
                            $delete_link = "<a href='admin/delete?username=".$item->username
                                    ."'>Delete</a>";
                            return  array($item->firstName,$item->lastName,$item->email,$view_link." | ".$delete_link);},$model);

                        $table->attributes = array('class'=>'table','id'=>'usersTable');

                    ?>

            @include('table',array('model'=>$table))

                </div>
             </div>
        </div>
         </div>
 </section>

    <div id="viewModal" class="reveal-modal" data-reveal></div>

@endsection
@section('scripts')

<script type="text/javascript">

        $(document).ready(function() {
            $('#usersTable').dataTable();
        });


</script>

@endsection
