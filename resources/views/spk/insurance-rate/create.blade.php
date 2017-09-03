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
	                  <label for="leasing_id" class="col-sm-2 control-label">Leasing</label>
	                  <div class="col-sm-10">
	                    <select name="leasing_id" class="form-control">
	                    	<option disabled="disabled" selected="selected">Choose Leasing</option>
	                    	@foreach($leasing as $key => $val)
	                    	<option @if(old('leasing_id') == $val->id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
	                    	@endforeach
	                    </select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="car_type_id" class="col-sm-2 control-label">Car Model</label>
	                  <div class="col-sm-10">
	                    <select name="car_model_id" class="form-control">
	                    	<option disabled="disabled" selected="selected">Choose Car Model</option>
	                    	@foreach($carModel as $key => $val)
	                    	<option @if(old('car_model_id') == $val->id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
	                    	@endforeach
	                    </select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="areas" class="col-sm-2 control-label">Areas</label>
	                  <div class="col-sm-10">
	                  	<select name="area[]" multiple="multiple" class="form-control">
	                  		@foreach($areas as $key => $val)
	                  		<option value="{{$val->id}}">{{$val->name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>
			         <table class="table table-striped leasing-rate">
	              		<thead>
	              			<tr>
	              				<th>Premi Type</th>
	              				<th>Car Years</th>
	              				<th>Rate</th>
	              				<th><a class="btn btn-default" style="cursor:pointer" onclick="addMonth()">Add Years</a></th>
	              			</tr>
	              		</thead>
	              		<tbody id="rate-details">
	              			<tr>
	              				<td>
	              					<select style="width:10em" name="type[]">
	              						<option value="1">All Risk</option>
	              						<option value="2">TLO</option>
	              					</select>
	              				</td>
	              				<td><input type="number" name="years[]" /></td>
	              				<td><input type="text" name="rate[]" /></td>
	              				<td><a style="cursor:pointer" class="btn btn-primary" onclick="removeMonth($(this))">Remove Years</a></td>
	              			</tr>
	              		</tbody>
	              	</table>
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