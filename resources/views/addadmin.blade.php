
<section class="collapsed bg-none">
    <div class="row">
        <div class="layout">
            <h4>User Search</h4>
            <div class="full-width">
                <div class="text">

<form action="{{url("/admin/searchResults")}}" method="post" id="usersearch">

<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

 <!-- Username -->
<div class="row">
  <label for="username">Username</label>
       <input type="text" name="username"/>

</div>


<!-- First Name -->
     <div class="row">
         <label for="firstname">First Name</label>
         <input type="text" name="firstName"/>

     </div>



<!-- Last Name-->
<div class="row">
  <label for="lastname">Last Name</label>

             <input type="text" name="lastName" />

</div>


<div class="row">
        <button type="submit" class="round tiny">Search</button>
        <button type="reset" class="round tiny">Clear</button>

</div>
</form>



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