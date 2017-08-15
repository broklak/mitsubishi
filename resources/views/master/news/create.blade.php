@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-12">
			<div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Create New {{ucwords($page)}}</h3>
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
	                  <label for="title" class="col-sm-2 control-label">Title</label>

	                  <div class="col-sm-10">
	                    <input type="text" class="form-control" name="title" value="{{old('title')}}" id="title" placeholder="Title">
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label for="content" class="col-sm-2 control-label">Content</label>

	                  <div class="col-sm-10">
	                    <textarea style="width:100%" name="content" rows="10" cols="80">
	                    {{old('content')}}
	                    </textarea>
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label for="file" class="col-sm-2 control-label">Image File</label>

	                  <div class="col-sm-10">
	                    <input type="file" class="form-control" name="image" id="file">
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