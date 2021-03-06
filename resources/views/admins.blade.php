@extends('app')

@section('content')

    <section class="collapsed bg-none section" id="content">
        <div class="row">
            <div class="layout">
                <div class="full-width">
                    <div class="text">

                        <p>  Table with a list of administrators will be shown here. </p>
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
 </section>

    <div id="viewModal" class="reveal-modal" data-reveal></div>

@endsection
@section('scripts')

    <script type="text/javascript" language="javascript" src="{{ asset('/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('/js/dataTables.foundation.js') }}"></script>
    <script type="text/javascript" src="{{asset("assets/bower_components/foundation/js/foundation/foundation.reveal.js")}}"></script>
    <script type="text/javascript" src="{{asset("js/tablesaw.stackonly.js")}}"></script>

<script type="text/javascript">
        $(document).ready(function() {
            $('#usersTable').dataTable({  "ordering": false,"info": false});
            $('#usersTable').table().data( "table" ).refresh();

        });

</script>

@endsection
