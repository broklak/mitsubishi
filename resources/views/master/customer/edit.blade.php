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
	            <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post" enctype="multipart/form-data">
	            	{{csrf_field()}}
	              <div class="box-body">
	                
	                <div class="form-group">
	                  <label for="first_name" class="col-sm-2 control-label">First Name</label>
	                  <div class="col-sm-10">
	                    <input type="text" class="form-control" name="first_name" value="{{$row->first_name}}" id="first_name" placeholder="First Name">
	                  </div>
	                </div>
	                
	                <div class="form-group">
	                  <label for="last_name" class="col-sm-2 control-label">Last Name</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="last_name" value="{{$row->last_name}}" id="last_name" placeholder="Last Name">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="id_type" class="col-sm-2 control-label">ID Type</label>
	                  <div class="col-sm-10">
	                  	<label class="radio-inline"><input @if($row->id_type == 1) checked="checked" @endif type="radio" value="1" name="id_type">KTP</label>
	                  	<label class="radio-inline"><input @if($row->id_type == 2) checked="checked" @endif type="radio" value="2" name="id_type">SIM</label>
	                  	<label class="radio-inline"><input @if($row->id_type == 3) checked="checked" @endif type="radio" value="3" name="id_type">Passport</label>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="id_number" class="col-sm-2 control-label">ID Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="id_number" value="{{$row->id_number}}" id="id_number" placeholder="ID Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="phone" class="col-sm-2 control-label">Phone Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="phone" value="{{$row->phone}}" id="phone" placeholder="Phone Number">
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

	                <div class="form-group">
	                  <label for="job" class="col-sm-2 control-label">Job</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="job" value="{{$row->job}}" id="job" placeholder="Job">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="npwp" class="col-sm-2 control-label">NPWP</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="npwp" value="{{$row->npwp}}" id="npwp" placeholder="NPWP">
	                  </div>
	                </div>

	                <div class="form-group">
	                  @php 
	                  	$folder = ($row->id_type == 1) ? 'ktp' : 'sim'; 
	                  	$folder = ($row->id_type == 3) ? 'passport' : $folder; 
	                  @endphp
	                  <div class="col-sm-2">
		               	  <img style="width:170px;height:120px" src="{{ asset('images') . '/customer/' . $folder . '/' . $row->image }}" />
		                  <label for="file" class="control-label">ID Image File</label>
	                  </div>
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
	              {{ method_field('PUT') }}
	            </form>
	          </div>
          </div>
    </section>

@endsection