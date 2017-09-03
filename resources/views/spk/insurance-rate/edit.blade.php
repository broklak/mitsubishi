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
	                  <label for="car_model_id" class="col-sm-2 control-label">Car Model</label>
	                  <div class="col-sm-10">
	                    <select name="car_model_id" class="form-control">
	                    	<option disabled="disabled" selected="selected">Choose Car Model</option>
	                    	@foreach($carModel as $key => $val)
	                    	<option @if($row->car_model_id == $val->id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
	                    	@endforeach
	                    </select>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="areas" class="col-sm-2 control-label">Areas</label>
	                  <div class="col-sm-10">
	                  	<select name="area[]" multiple="multiple" class="form-control">
	                  		@php $area = explode(',', $row->area); @endphp
	                  		@foreach($areas as $key => $val)
	                  		<option @if(in_array($val->id, $area)) selected @endif value="{{$val->id}}">{{$val->name}}</option>
	                  		@endforeach
	                  	</select>
	                  </div>
	                </div>
			         <table class="table table-striped leasing-rate">
	              		<thead>
	              			<tr>
	              				<th>Premi Type</th>
	              				<th>Car Years</th>
	              				<th>Rate (%) * Use (.) for decimal</th>
	              				<th><a class="btn btn-default" style="cursor:pointer" onclick="addMonth()">Add Months</a></th>
	              			</tr>
	              		</thead>
	              		<tbody id="rate-details">
	              			@foreach($detail as $key => $val)
	              			<tr>
	              				<td>
	              					<select style="width:10em" name="type[]">
	              						<option @if($val->type == 1) selected @endif value="1">All Risk</option>
	              						<option @if($val->type == 2) selected @endif value="2">TLO</option>
	              					</select>
	              				</td>
	              				<td><input type="number" value="{{$val->years}}" name="years[]" /></td>
	              				<td><input type="text" value="{{$val->rate}}" name="rate[]" /></td>
	              				<td>
	              					<a style="cursor:pointer" class="btn btn-primary" onclick="removeMonth($(this))">Remove Months</a>
	              				</td>
	              			</tr>
	              			@endforeach
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
	              {{ method_field('PUT') }}
	            </form>
	          </div>
          </div>
    </section>

@endsection