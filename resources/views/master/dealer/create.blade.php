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
	                  <label for="code" class="col-sm-2 control-label">Company</label>
	                  <div class="col-sm-10">
	                  	<select class="form-control" name="company_id">
	                  		<option value="0" disabled="disabled" selected="selected">Select Company</option>
	                  		@foreach($company as $key => $val)
	                  		<option @if(old('company_id') == $val->id ) selected="selected" @endif value="{{$val->id}}">{{$val->name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>
	                
	                <div class="form-group">
	                  <label for="code" class="col-sm-2 control-label">Contact Person Name</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="contact_name" value="{{old('contact_name')}}" id="name" placeholder="Contact Person">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="phone" class="col-sm-2 control-label">Phone Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="phone" value="{{old('phone')}}" id="phone" placeholder="Phone Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="fax" class="col-sm-2 control-label">Fax Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="fax" value="{{old('fax')}}" id="fax" placeholder="Fax Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="email" class="col-sm-2 control-label">Email</label>
	                  <div class="col-sm-10">
	                  	<input type="email" class="form-control" name="email" value="{{old('email')}}" id="email" placeholder="Email Address">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="address" class="col-sm-2 control-label">Address</label>
	                  <div class="col-sm-10">
	                  	<textarea rows="5" name="address" style="width:100%"></textarea>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="areas" class="col-sm-2 control-label">Areas</label>
	                  <div class="col-sm-10">
	                  	<select name="area" class="form-control">
	                  		@foreach($area as $key => $val)
	                  		<option value="{{$val->id}}">{{$val->name}}</option>
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