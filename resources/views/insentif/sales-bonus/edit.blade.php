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
			         <table class="table table-striped leasing-rate">
	              		<thead>
	              			<tr>
	              				<th>Minimum Car</th>
	              				<th>Maximum Car</th>
	              				<th>Bonus Amount</th>
	              				<th><a class="btn btn-default" style="cursor:pointer" onclick="addMonth()">Add Bonus Amount</a></th>
	              			</tr>
	              		</thead>
	              		<tbody id="rate-details">
	              			@foreach($detail as $key => $val)
	              			<tr>
	              				<td><input type="number" value="{{$val->min_car}}" name="min[]" /></td>
	              				<td><input type="number" value="{{$val->max_car}}" name="max[]" /></td>
	              				<td><input type="text" value="{{moneyFormat($val->amount)}}" onkeyup="formatMoney($(this))" name="amount[]" /></td>
	              				<td>
	              					<a style="cursor:pointer" class="btn btn-primary" onclick="removeMonth($(this))">Remove</a>
	              				</td>
	              			</tr>
	              			@endforeach
	              			<tr>
	              				<td><input type="number" name="min[]" /></td>
	              				<td><input type="number" name="max[]" /></td>
	              				<td><input type="text" name="amount[]" onkeyup="formatMoney($(this))" /></td>
	              				<td><a style="cursor:pointer" class="btn btn-primary" onclick="removeMonth($(this))">Remove</a></td>
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