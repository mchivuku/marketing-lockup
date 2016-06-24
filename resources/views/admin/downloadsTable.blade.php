@extends('app')

@section('content')
    <section class="extra-space bg-none section" id="content">
        <div class="row">
            <div class="layout">
                <div class="full-width">

    @include('table',array('model'=>$model))
                    </div></div></div></section>
<div id="viewModal" class="reveal-modal" data-reveal></div>
@endsection

@section("scripts")

    <script type="text/javascript"
            language="javascript"
            src="{{ asset('/js/table.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#downloadsTable').dataTable
            ({"ordering": false, "info": false,
                "aLengthMenu": [ [50, 100, 150, -1], [50, 100, 150, "All"] ],
                "iDisplayLength" : 50

            });

        });

    </script>
@endsection

