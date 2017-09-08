@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post" enctype="multipart/form-data">
    	@foreach($errors->all() as $message)
		   	<div style="margin: 20px 0" class="alert alert-error">
		        {{$message}}
		     </div>
		@endforeach
	    {{csrf_field()}}
	    <div class="col-md-12">
	    	<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Credit Simulation</h3>
					</div>
		            <div class="box-body">

			            <div class="form-group">
			                <label for="date" class="col-sm-2 control-label">Leasing</label>
			                <div class="col-sm-10">
			                   <select name="leasing_id" class="form-control" id="leasing_id">
			                   		<option disabled="disabled" selected="selected" value="0">Choose Leasing</option>
			                   		@foreach($leasing as $key => $val)
			                   		<option @if($row->leasing_id == $val->id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
			                   		@endforeach
			                   </select>
			                   @foreach($leasing as $key => $val)
				               <input type="hidden" id="admin_cost_leasing_{{$val->id}}" value="{{$val->admin_cost}}" />
				               @endforeach
				               <input type="hidden" id="admin_cost_leasing_0" value="0" />
			                </div>
			             </div>

			            <div class="form-group">
			               <label for="car_type" class="col-sm-2 control-label">Car Type</label>
			               <div class="col-sm-10">
			               		<input type="text" class="form-control" name="type_name" value="{{App\Models\CarModel::getName($row->car_model_id) . ' ' . App\Models\CarType::getName($row->car_type_id)}}" id="type_id" placeholder="Car Type">
			               		<input type="hidden" value="{{$row->car_type_id}}" id="type_id_real" name="type_id" />
			               </div>
			            </div>

			            <div class="form-group">
		                  <label for="customer_name" class="col-sm-2 control-label">Customer Name</label>
		                  <div class="col-sm-10">
		                    <input type="text" class="form-control" name="customer_name" value="{{$row->customer_name}}" id="customer_name" placeholder="Enter Customer Name">
		                  </div>
		                </div>

			             <div class="form-group">
			               <label for="car_year" class="col-sm-2 control-label">Car Built Year</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="car_year" value="{{$row->car_year}}" id="car_year" placeholder="Enter Year">
			               </div>
			             </div>

			             <div class="form-group">
			               <label for="price" class="col-sm-2 control-label">Car Price</label>
			               <div class="col-sm-10">
			                  <input type="text" onkeyup="formatMoney($(this))" class="form-control" name="total_sales_price" value="{{moneyFormat($row->price)}}" id="total_sales_price" placeholder="Enter Price">
			                  <input type="hidden" id="total_unpaid" name="total_unpaid" value="{{$row->price - $row->dp_amount}}">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="dp_percentage" class="col-sm-2 control-label">DP (%)</label>
			               <div class="col-sm-10">
			                  <input type="number" class="form-control" name="dp_percentage" value="{{$row->dp_percentage}}" id="dp_percentage" placeholder="Enter DP Percentage">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="dp_amount" class="col-sm-2 control-label">DP (IDR)</label>
			               <div class="col-sm-10">
			                  <input type="text" onkeyup="formatMoney($(this))" class="form-control" name="dp_amount" value="{{moneyFormat($row->dp_amount)}}" id="dp_amount" placeholder="Enter DP Amount">
			               </div>
			            </div>

			            <div class="form-group">
			                <label for="date" class="col-sm-2 control-label">Credit Duration</label>
			                <div class="col-sm-10">
			                   <select name="duration" class="form-control" id="credit_duration">
			                   		<option disabled="disabled" selected="selected" value="0">Choose Month Duration</option>
			                   		@foreach($months as $key => $val)
			                   		<option @if($row->duration == $val->months) selected @endif value="{{$val->months}}">{{$val->months}} Months</option>
			                   		@endforeach
			                   </select>
			                </div>
			             </div>

			             <div class="col-md-12" style="text-align:center">
				       		<a onclick="getCalculation()" style="cursor:pointer" style="width:100%" class="btn btn-primary">CALCULATE</a>
				       </div>

		            </div>
		        </div>

		        <div class="box box-info" id="calculation">
					<div class="box-header with-border">
						<h3 class="box-title">Calculation</h3>
					</div>
		            <div class="box-body">

		            	<div class="form-group">
			               <label for="admin_cost" class="col-sm-2 control-label">Admin Cost</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="admin_cost" value="{{moneyFormat($row->admin_cost)}}" id="admin_cost">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="interest_rate" class="col-sm-2 control-label">Interest Rate</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="interest_rate" value="{{$row->interest_rate}}" id="interest_rate">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="installment_cost" class="col-sm-2 control-label">Installment Cost</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="installment_cost" value="{{moneyFormat($row->installment_cost)}}" id="installment_cost" readonly>
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="insurance_cost" class="col-sm-2 control-label">Insurance Cost</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="insurance_cost" value="{{moneyFormat($row->insurance_cost)}}" id="insurance_cost">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="total_dp" class="col-sm-2 control-label">Total Down Payment</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="total_dp" value="{{moneyFormat($row->total_dp)}}" id="total_down_payment" readonly>
			               </div>
			            </div>

		            </div>
		        </div>
		        <div class="col-md-12">
				    <button type="submit" style="width:100%" class="btn btn-primary">SAVE SIMULATION</button>
				</div>
	        </div>

        </div>
        <div style="clear:both"></div>
        {{ method_field('PUT') }}
    </form>
    </section>

@endsection