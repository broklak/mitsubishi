@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-12">
			<div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Add New Approver</h3>
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
	                  <label for="name" class="col-sm-2 control-label">Role</label>
	                  <div class="col-sm-10">
	                  	<select name="job_position_id" class="form-control">
	                  		@foreach($role as $key => $val)
	                  		<option value="{{$val->id}}">{{$val->display_name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="level" class="col-sm-2 control-label">Approval Level</label>
	                  <div class="col-sm-10">
	                    <input type="number" class="form-control" name="level" value="{{old('level')}}" id="level" placeholder="Level">
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