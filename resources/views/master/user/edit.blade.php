@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-12">
			<div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Edit {{ucwords(str_replace('-',' ', $page))}}</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            @foreach($errors->all() as $message)
		            <div style="margin: 20px 0" class="alert alert-error">
		                {{$message}}
		            </div>
		        @endforeach
		        {!! session('displayMessage') !!}
	            <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post" enctype="multipart/form-data">
	            	{{csrf_field()}}
	              <div class="box-body">
	                
	                <div class="form-group">
	                  <label for="first_name" class="col-sm-4 control-label">First Name</label>
	                  <div class="col-sm-8">
	                    <input type="text" class="form-control" name="first_name" value="{{$row->first_name}}" id="first_name" placeholder="First Name">
	                  </div>
	                </div>
	                
	                <div class="form-group">
	                  <label for="last_name" class="col-sm-4 control-label">Last Name</label>
	                  <div class="col-sm-8">
	                  	<input type="text" class="form-control" name="last_name" value="{{$row->last_name}}" id="last_name" placeholder="Last Name">
	                  </div>
	                </div>

	                <div class="form-group">
			            <label for="start_work" class="col-sm-4 control-label">Start Working Date</label>
			            <div class="col-sm-8">
			               	<input type="text" class="form-control datepicker" name="start_work" value="{{$row->start_work}}" id="start_work" placeholder="Start Working Date">
			            </div>
			         </div>

			         <div class="form-group">
	                  <label for="duration" class="col-sm-4 control-label">Login Validity Duration *days</label>
	                  <div class="col-sm-4">
	                  	<input type="number" style="display: {{($row->extend_duration >365) ? 'none' : 'block' }}" class="form-control" name="duration" 
	                  	value="{{($row->extend_duration > 365) ? 90 : $row->extend_duration}}" id="duration" placeholder="Total days without approved SPK">
	                  	<span style="display: {{($row->extend_duration >365) ? 'block' : 'none' }};font-size: 16px;font-weight: 600" id="alltimespan">All Time</span>
	                  </div>
	                  <div class="col-sm-4">
	                  	<input type="checkbox" @if($row->extend_duration > 365) checked @endif style="margin-right: 7px" name="alltime" value="1" id="alltime"><label for="alltime">All Time</label>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="file" class="col-sm-4 control-label">Password *Fill password if you want to change</label>
	                  <div class="col-sm-8">
	                    <input type="password" class="form-control" name="password" id="pass">
	                    <input type="hidden" name="type" value="{{$type}}" />
	                  </div>
	                </div>	  

	                <div class="form-group">
	                  <label for="file" class="col-sm-4 control-label">Access Role (You can select more than 1)</label>
	                  <div class="col-sm-8">
	                  	<select name="roles[]" multiple class="form-control">
	                  		@foreach($position as $key => $val)
	                  		<option @if(in_array($val->id, $validRole)) selected @endif value="{{$val->id}}">{{$val->display_name}}</option>
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
	                  		<option @if($val->id == $row->supervisor_id) selected @endif value="{{$val->id}}">{{$val->first_name . ' ' . $val->last_name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="file" class="col-sm-4 control-label">Assign Dealer *You can choose multiple</label>
	                  <div class="col-sm-8">
	                  	<select name="dealer_id[]" class="form-control" multiple="multiple">
	                  		@foreach($dealer as $key => $val)
	                  		<option @if(in_array($val->id, $assignDealer)) selected @endif value="{{$val->id}}">{{$val->name}}</option>
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
	              {{ method_field('PUT') }}
	            </form>
	          </div>
          </div>
    </section>

@endsection