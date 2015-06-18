 <form class="form-horizontal" action="{{url("/admin/searchResults")}}" method="post" id="usersearch">

<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<!-- Username -->
<div class="control-group">
  <label class="control-label" for="username">Username</label>
  <div class="controls">
    <div class="form-control-static">
                     <input type="text" name="username"/>
            </div>
    </div>
  </div>

<!-- First Name -->
<div class="control-group">
  <label class="control-label" for="firstname">First Name</label>
  <div class="controls">
   <div class="form-control-static">
             <input type="text" name="firstName"/>
          </div>
  </div>
</div>

<!-- Last Name-->
<div class="control-group">
  <label class="control-label" for="lastname">Last Name</label>
  <div class="controls">
     <div class="form-control-static">
             <input type="text" name="lastName" />
            </div>
    </div>
</div>


<div class="row">
  <div class="col-lg-10 pull-right">
        <button type="submit" class="btn btn-primary btn-small">Search</button>
        <button type="reset" class="btn btn-small">Clear</button>
        </div>
</div>

</form>

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