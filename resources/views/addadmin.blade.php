
<section class="collapsed bg-none">
    <div class="row">
        <div class="layout">
            <h4>User Search</h4>
            <div class="full-width">
                <div class="text">

{!! Form::open(array('url' => '/admin/searchResults','id'=>"usersearch")) !!}
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



<div id="results"/>
   </div></div></div></div></section>
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