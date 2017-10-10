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
	                  <label for="phone" class="col-sm-2 control-label">Phone Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="phone" value="{{$row->phone}}" id="phone" placeholder="Phone Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="phone_home" class="col-sm-2 control-label">Office / Home Phone Number</label>
	                  <div class="col-sm-10">
	                  	<input type="text" class="form-control" name="phone_home" value="{{$row->phone_home}}" id="phone_home" placeholder="Phone Number">
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

	              </div>
	              <!-- /.box-body -->
	              
	              <!-- /.box-footer -->
	              {{ method_field('PUT') }}

	              <div class="nav-tabs-custom">
		            <ul class="nav nav-tabs">
		              <li class="active"><a href="#tab_1" data-toggle="tab">KTP</a></li>
		              <li><a href="#tab_2" data-toggle="tab">SIM</a></li>
		              <li><a href="#tab_3" data-toggle="tab">Passport</a></li>
		            </ul>
		            <div class="tab-content">
		              <div class="tab-pane active" id="tab_1">
		                  <table class="table table-bordered table-hover table-striped">
		                    <thead>
		                      <tr>
		                        <th>Image <a data-toggle="modal" data-target="#modal-ktp" style="margin-left: 10px" class="btn btn-info">Attach New KTP</a></th>
		                        <th>Number</th>
		                        <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                      @foreach($ktp as $key => $val)
		                        <tr>
		                          <td>
		                            <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/ktp/' . $val->filename }}" />
		                          </td>
		                          <td>{{$val->id_number}}</td>
		                          <td>
		                            <a onclick="return confirm('You will delete this ID Image. Continue?')" class="btn btn-primary" href="{{ route('customer.deleteImage', ['id' => $val->id]) }}">Delete</a>
		                          </td>
		                        </tr>
		                      @endforeach
		                    </tbody>
		                  </table>
		              </div>
		              <!-- /.tab-pane -->
		              <div class="tab-pane" id="tab_2">
		                  <table class="table table-bordered table-hover table-striped">
		                    <thead>
		                      <tr>
		                        <th>Image <a data-toggle="modal" data-target="#modal-sim" style="margin-left: 10px" class="btn btn-info">Attach New SIM</a></th>
		                        <th>Number</th>
		                        <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                      @foreach($sim as $key => $val)
		                        <tr>
		                          <td>
		                            <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/sim/' . $val->filename }}" />
		                          </td>
		                          <td>{{$val->id_number}}</td>
		                          <td>
		                            <a onclick="return confirm('You will delete this ID Image. Continue?')" class="btn btn-primary" href="{{ route('customer.deleteImage', ['id' => $val->id]) }}"">Delete</a>
		                          </td>
		                        </tr>
		                      @endforeach
		                    </tbody>
		                  </table>
		              </div>
		              <!-- /.tab-pane -->
		              <div class="tab-pane" id="tab_3">
		                  <table class="table table-bordered table-hover table-striped">
		                    <thead>
		                      <tr>
		                        <th>Image <a data-toggle="modal" data-target="#modal-passport" style="margin-left: 10px" class="btn btn-info">Attach New Passport</a></th>
		                        <th>Number</th>
		                        <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                      @foreach($passport as $key => $val)
		                        <tr>
		                          <td>
		                            <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/passport/' . $val->filename }}" />
		                          </td>
		                          <td>{{$val->id_number}}</td>
		                          <td>
		                            <a onclick="return confirm('You will delete this ID Image. Continue?')" class="btn btn-primary" href="{{ route('customer.deleteImage', ['id' => $val->id]) }}">Delete</a>
		                          </td>
		                        </tr>
		                      @endforeach
		                    </tbody>
		                  </table>
		              </div>
		              <!-- /.tab-pane -->
		            </div>
		            <!-- /.tab-content -->
		            <div class="box-footer">
	                	<button type="submit" class="btn btn-info pull-right">Submit</button>
	              	</div>
		          </div>
	            </form>
	          </div>
          </div>

          <!-- START MODAL KTP REASON -->
		       						<div class="modal modal-danger fade" id="modal-ktp">
		       							<form class="form-horizontal" method="post" action="{{route('customer.image.add')}}" enctype="multipart/form-data">
		       							{{csrf_field()}}
								          <div class="modal-dialog">
								            <div class="modal-content">
								              <div class="modal-header">
								                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                  <span aria-hidden="true">&times;</span>
								                 </button>
								                <h4 class="modal-title">Attach New KTP</h4>
								              </div>
								              <div class="modal-body">
								              	<div class="form-group">
								              		<label class="control-label">KTP Number</label>
								              		<input class="form-control" type="text" required name="id_number">
								              	</div>
								              	<div class="form-group">
								              		<label class="control-label">Image</label>
								                	<input type="file" name="image">
								                	<input type="hidden" name="type" value="1">
								                	<input type="hidden" name="customer_id" value="{{$row->id}}">
								              	</div>
								                	
								              </div>
								              <div class="modal-footer">
								                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
								                <button type="submit" class="btn btn-outline">Save</button>
								              </div>
								              </form>
								            </div>
								            <!-- /.modal-content -->
								          </div>
								          <!-- /.modal-dialog -->
								    </div>
								    <!-- END MODAL KTP REASON -->


	<!-- START MODAL SIM REASON -->
		       						<div class="modal modal-danger fade" id="modal-sim">
		       							<form class="form-horizontal" method="post" action="{{route('customer.image.add')}}" enctype="multipart/form-data">
		       							{{csrf_field()}}
								          <div class="modal-dialog">
								            <div class="modal-content">
								              <div class="modal-header">
								                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                  <span aria-hidden="true">&times;</span>
								                 </button>
								                <h4 class="modal-title">Attach New SIM</h4>
								              </div>
								              <div class="modal-body">
								              	<div class="form-group">
								              		<label class="control-label">SIM Number</label>
								              		<input class="form-control" type="text" required name="id_number">
								              	</div>
								              	<div class="form-group">
								              		<label class="control-label">Image</label>
								                	<input type="file" name="image">
								                	<input type="hidden" name="type" value="2">
								                	<input type="hidden" name="customer_id" value="{{$row->id}}">
								              	</div>
								                	
								              </div>
								              <div class="modal-footer">
								                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
								                <button type="submit" class="btn btn-outline">Save</button>
								              </div>
								              </form>
								            </div>
								            <!-- /.modal-content -->
								          </div>
								          <!-- /.modal-dialog -->
								    </div>
								    <!-- END MODAL SIM REASON -->


	<!-- START MODAL Passport REASON -->
		       						<div class="modal modal-danger fade" id="modal-passport">
		       							<form class="form-horizontal" method="post" action="{{route('customer.image.add')}}" enctype="multipart/form-data">
		       							{{csrf_field()}}
								          <div class="modal-dialog">
								            <div class="modal-content">
								              <div class="modal-header">
								                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                  <span aria-hidden="true">&times;</span>
								                 </button>
								                <h4 class="modal-title">Attach New Passport</h4>
								              </div>
								              <div class="modal-body">
								              	<div class="form-group">
								              		<label class="control-label">Passport Number</label>
								              		<input class="form-control" type="text" required name="id_number">
								              	</div>
								              	<div class="form-group">
								              		<label class="control-label">Image</label>
								                	<input type="file" name="image">
								                	<input type="hidden" name="type" value="3">
								                	<input type="hidden" name="customer_id" value="{{$row->id}}">
								              	</div>
								                	
								              </div>
								              <div class="modal-footer">
								                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
								                <button type="submit" class="btn btn-outline">Save</button>
								              </div>
								              </form>
								            </div>
								            <!-- /.modal-content -->
								          </div>
								          <!-- /.modal-dialog -->
								    </div>
								    <!-- END MODAL SIM REASON -->

    </section>

@endsection