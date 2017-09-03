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
	            <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post">
	            	{{csrf_field()}}

	            	<div class="box-body">
	                <div class="form-group">
	                  <label for="leasing_id" class="col-sm-2 control-label">Leasing</label>
	                  <div class="col-sm-10">
	                    <select name="leasing_id" class="form-control">
	                    	<option disabled="disabled" selected="selected">Choose Leasing</option>
	                    	@foreach($leasing as $key => $val)
	                    	<option @if($row->leasing_id == $val->id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
	                    	@endforeach
	                    </select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="car_type_id" class="col-sm-2 control-label">Car Type Id</label>
	                  <div class="col-sm-10">
	                    <select name="car_type_id" class="form-control">
	                    	<option disabled="disabled" selected="selected">Choose Car Type</option>
	                    	@foreach($carType as $key => $val)
	                    	<option @if($row->car_type_id == $val->id) selected @endif value="{{$val->id}}">{{\App\Models\CarModel::getName($val->model_id)}} - {{$val->name}}</option>
	                    	@endforeach
	                    </select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="areas" class="col-sm-2 control-label">Areas</label>
	                  <div class="col-sm-10">
	                  	<select name="areas[]" multiple="multiple" class="form-control">
	                  		@php $area = explode(',', $row->areas); @endphp
	                  		@foreach($areas as $key => $val)
	                  		<option @if(in_array($val->id, $area)) selected @endif value="{{$val->id}}">{{$val->name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>

	                <div class="form-group">
			            <label for="start_date" class="col-sm-2 control-label">Start Date</label>
			            <div class="col-sm-10">
			               	<input type="text" class="form-control datepicker" name="start_date" value="{{$row->start_date}}" id="start_date" placeholder="Start Date">
			            </div>
			         </div>

			         <div class="form-group">
			            <label for="end_date" class="col-sm-2 control-label">End Date</label>
			            <div class="col-sm-10">
			               	<input type="text" class="form-control datepicker" name="end_date" value="{{$row->end_date}}" id="end_date" placeholder="End Date">
			            </div>
			         </div>

			         <div class="form-group">
			            <label for="karoseri" class="col-sm-2 control-label">Karoseri Price</label>
			            <div class="col-sm-10">
			               	<input type="text" onkeyup="formatMoney($(this))" class="form-control" name="karoseri" value="{{$row->karoseri}}" id="karoseri" placeholder="Enter Karoseri Price">
			            </div>
			         </div>
			         <table class="table table-striped leasing-rate">
	              		<thead>
	              			<tr>
	              				<th>Months</th>
	              				<th>DP Minimum (%)</th>
	              				<th>DP Maximum (%)</th>
	              				<th>Rate (%) * Use (.) for decimal</th>
	              				<th><a class="btn btn-default" style="cursor:pointer" onclick="addMonth()">Add Months</a></th>
	              			</tr>
	              		</thead>
	              		<tbody id="rate-details">
	              			@foreach($detail as $key => $val)
	              			<tr>
	              				<td><input type="number" value="{{$val->months}}" name="months[]" /></td>
	              				<td><input type="number" value="{{$val->dp_min}}" name="dp_min[]" /></td>
	              				<td><input type="number" value="{{$val->dp_max}}" name="dp_max[]" /></td>
	              				<td><input type="text" value="{{$val->rate}}" name="rate[]" /></td>
	              				<td>
	              					<a style="cursor:pointer" class="btn btn-primary" onclick="removeMonth($(this))">Remove Months</a>
	              				</td>
	              			</tr>
	              			@endforeach
	              			<tr>
	              				<td><input type="number" name="months[]" /></td>
	              				<td><input type="number" name="dp_min[]" /></td>
	              				<td><input type="number" name="dp_max[]" /></td>
	              				<td><input type="text" name="rate[]" /></td>
	              				<td><a style="cursor:pointer" class="btn btn-primary" onclick="removeMonth($(this))">Remove Months</a></td>
	              			</tr>
	              		</tbody>
	              	</table>
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