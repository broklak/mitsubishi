@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-12">
			<div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Create {{ucwords(str_replace('-',' ', $page))}}</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            @foreach($errors->all() as $message)
		            <div style="margin: 20px 0" class="alert alert-error">
		                {{$message}}
		            </div>
		        @endforeach
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
	                  <label for="file" class="col-sm-4 control-label">Password *Fill password if you want to change</label>
	                  <div class="col-sm-8">
	                    <input type="password" class="form-control" name="password" id="pass">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="file" class="col-sm-4 control-label">Position</label>
	                  <div class="col-sm-8">
	                  	<select name="job_position_id" class="form-control">
	                  		<option value="0" disabled="disabled" selected="selected">Choose Position</option>
	                  		@foreach($position as $key => $val)
	                  		<option @if($row->job_position_id == $val->id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
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