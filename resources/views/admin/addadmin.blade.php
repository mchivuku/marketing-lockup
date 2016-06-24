<div class="panel">

    <div class="panel-heading">
        <p>Search for the user to add in the university database</p></div>

    <div class="panel-body">


        {!! Form::open(array('url' => \URL::to("admin/administrators/searchResults"),'id'=>"usersearch")) !!}
        <div class="row">
            {!!  Form::label('username', 'Username') !!}
            {!!  Form::text('username') !!}
        </div>
        <div class="row">
            {!!  Form::label('firstname', 'First Name') !!}
            {!!  Form::text('firstName') !!}
        </div>

        <div class="row">
            {!!  Form::label('lastName', 'Last Name') !!}
            {!!  Form::text('lastName') !!}
        </div>



        <div class="row">
            {!! Form::button('Search',array('class'=>'round tiny','type'=>'submit')) !!}
            {!! Form::button('Clear',array('class'=>'round tiny','type'=>'reset')) !!}


        </div>
        {!! Form::close() !!}



    </div></div>

<div id="results"/>
<script type="text/javascript">
    $(document).ready(function(){
        $("#usersearch").submit(function(event) {

            event.preventDefault();
            var url =$(this).attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: $("#usersearch").serialize(), // serializes the form's elements.
                success: function(data)
                {

                    $('#results').empty().html(data);
                }
            });

            return false; // avoid to execute the actual submit of the form.
        });
    });
</script>