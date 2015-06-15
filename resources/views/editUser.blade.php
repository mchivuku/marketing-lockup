 <form class="form-horizontal" action="{{url("/users/save")}}" method="POST">


<!-- First Name -->
<div class="control-group">
  <label class="control-label" for="inputFirstName">First Name</label>
  <div class="controls">
   <div class="form-control-static">
             {{$model->user->firstName}}
          </div>
  </div>
</div>


<!-- Last Name-->
<div class="control-group">
  <label class="control-label" for="inputFirstName">Last Name</label>
  <div class="controls">
    <div class="form-control-static">
               {{$model->user->lastName}}
            </div>
    </div>
  </div>



<!-- Email Address -->
<div class="control-group">
  <label class="control-label" for="inputFirstName">Email Address</label>
  <div class="controls">
    <div class="form-control-static">
               {{$model->user->email}}
            </div>
    </div>
  </div>

<div class="control-group">
  <label class="control-label" for="selectrole">Role</label>
  <div class="controls">
    <select id="selectrole" name="role" class="input-xlarge">
      <?foreach($model->roles as $role){

      	if($model->user->roleId==$role->id){
      		echo sprintf("<option value='%s' selected>%s</option>",$role->id,$role->name);

      	}else{
      		echo sprintf("<option value='%s'>%s</option>",$role->id,$role->name);

      	}

      }?>

    </select>
  </div>
</div>


<input type="hidden" value="{{$model->user->username}}" name="username"/>
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="row">
  <div class="col-lg-10 pull-right">
         <button type="submit" class="btn btn-primary btn-small">Save Changes</button>
            <button type="buttom" class="btn btn-small"  data-dismiss="modal">Cancel</button>
        </div>
</div>



</form>
