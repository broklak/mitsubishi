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
	            <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post">
	            	{{csrf_field()}}
	              <div class="box-body">
	                <div class="form-group">
	                  <label for="name" class="col-sm-2 control-label">Name</label>
	                  <div class="col-sm-10">
	                    <input type="text" class="form-control" name="name" value="{{$row->name}}" id="name" placeholder="Name">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="code" class="col-sm-2 control-label">Contact Person Name</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="contact_name" value="{{$row->contact_name}}" id="name" placeholder="Contact Person">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="phone" class="col-sm-2 control-label">Phone Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="phone" value="{{$row->phone}}" id="phone" placeholder="Phone Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="fax" class="col-sm-2 control-label">Fax Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="fax" value="{{$row->fax}}" id="fax" placeholder="Fax Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="email" class="col-sm-2 control-label">Email</label>
	                  <div class="col-sm-10">
	                  	<input type="email" class="form-control" name="email" value="{{$row->email}}" id="email" placeholder="Email Address">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="address" class="col-sm-2 control-label">Address</label>
	                  <div class="col-sm-10">
	                  	<textarea rows="5" name="address" style="width:100%">{{$row->address}}</textarea>
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