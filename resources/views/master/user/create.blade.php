@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-12">
			<div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Create New {{ucwords(str_replace('-',' ', $page))}}</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            @foreach($errors->all() as $message)
		            <div style="margin: 20px 0" class="alert alert-error">
		                {{$message}}
		            </div>
		        @endforeach
	            <form class="form-horizontal" action="{{route("$page.store")}}" method="post" enctype="multipart/form-data">
	            	{{csrf_field()}}
	              <div class="box-body">
	                <div class="form-group">
	                  <label for="first_name" class="col-sm-4 control-label">First Name</label>
	                  <div class="col-sm-8">
	                    <input type="text" class="form-control" name="first_name" value="{{old('first_name')}}" id="first_name" placeholder="First Name">
	                  </div>
	                </div>
	                
	                <div class="form-group">
	                  <label for="last_name" class="col-sm-4 control-label">Last Name</label>
	                  <div class="col-sm-8">
	                  	<input type="text" class="form-control" name="last_name" value="{{old('last_name')}}" id="last_name" placeholder="Last Name">
	                  </div>
	                </div>

	                <div class="form-group">
			            <label for="start_work" class="col-sm-4 control-label">Start Working Date</label>
			            <div class="col-sm-8">
			               	<input type="text" class="form-control datepicker" name="start_work" value="{{old('start_work')}}" id="start_work" placeholder="Start Work Date">
			            </div>
			         </div>

			         <div class="form-group">
	                  <label for="duration" class="col-sm-4 control-label">Login Validity Duration</label>
	                  <div class="col-sm-4">
	                  	<input type="number" class="form-control" name="duration" value="{{old('duration')}}" id="duration" placeholder="Total days without approved SPK">
	                  	<span style="display: none;font-size: 16px;font-weight: 600" id="alltimespan">All Time</span>
	                  </div>
	                  <div class="col-sm-4">
	                  	<input type="checkbox" style="margin-right: 7px" name="alltime" value="1" id="alltime"><label for="alltime">All Time</label>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="username" class="col-sm-4 control-label">Username *For Login</label>
	                  <div class="col-sm-8">
	                  	<input type="text" class="form-control" name="username" value="{{old('username')}}" id="username" placeholder="Username">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="file" class="col-sm-4 control-label">Password *Min 4 Characters</label>
	                  <div class="col-sm-8">
	                    <input type="password" class="form-control" name="password" id="pass">
	                  </div>
	                </div>              

	                <div class="form-group">
	                  <label for="file" class="col-sm-4 control-label">Access Role (You can select more than 1)</label>
	                  <div class="col-sm-8">
	                  	<select name="roles[]" multiple class="form-control">
	                  		@foreach($position as $key => $val)
	                  		<option value="{{$val->id}}">{{$val->display_name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="supervisor_id" class="col-sm-4 control-label">Supervisor (only for sales)</label>
	                  <div class="col-sm-8">
	                  	<select name="supervisor_id" class="form-control">
	                  		<option value="0" selected disabled>Choose Supervisor</option>
	                  		@foreach($supervisor as $key => $val)
	                  		<option @if(old('supervisor_id') == $val->id) selected @endif value="{{$val->id}}">{{$val->first_name . ' ' . $val->last_name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>	  

	                <div class="form-group">
	                  <label for="file" class="col-sm-4 control-label">Assign Dealer *You can choose multiple</label>
	                  <div class="col-sm-8">
	                  	<select name="dealer_id[]" class="form-control" multiple="multiple">
	                  		@foreach($dealer as $key => $val)
	                  		<option @if(is_array(old('dealer_id')) && in_array($val->id, old('dealer_id'))) selected @endif value="{{$val->id}}">{{$val->name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>

	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	                <button type="submit" class="btn btn-info pull-right">Submit</button>
	              </div>
	              <!-- /.box-footer -->
	            </form>
	          </div>
          </div>
    </section>

@endsection