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
	                  <label for="name" class="col-sm-2 control-label">Name</label>
	                  <div class="col-sm-10">
	                    <input type="text" class="form-control" name="name" value="{{old('name')}}" id="name" placeholder="Name">
	                  </div>
	                </div>
	                
	                <div class="form-group">
	                  <label for="code" class="col-sm-2 control-label">Car Model</label>
	                  <div class="col-sm-10">
	                  	<select class="form-control" name="model_id">
	                  		<option value="0">Select Model</option>
	                  		@foreach($model as $key => $val)
	                  		<option @if(old('model_id') == $val->id ) selected="selected" @endif value="{{$val->id}}">{{$val->name}}</option>
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