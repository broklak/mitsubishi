<div class="col-md-6" id="leasing-container" style="display:{{($init['payment_method'] == 2) ? 'block' : 'none'}}">
	          	<div class="box box-info">
	          		<div class="box-header with-border">
						<h3 class="box-title">Leasing Data</h3>
					</div>
	          		<div class="box-body">
		        		<div class="form-group">
			               <label for="leasing_id" class="col-sm-3 control-label">Leasing</label>
			               <div class="col-sm-9">
				               <select name="leasing_id" class="form-control">
				               		<option value="0">Choose Leasing</option>
				               		@foreach($leasing as $key => $val)
				               		<option @if($init['leasing_id'] == $val->id) selected="selected" @endif value="{{$val->id}}">{{$val->name}}</option>
				               		@endforeach
				               </select>
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="year_duration" class="col-sm-3 control-label">Credit Duration</label>
			               <div class="col-sm-9">
				               <select name="year_duration" class="form-control">
				               		<option value="0">Choose Duration</option>
				               		<option @if($init['year_duration'] == 1) selected="selected" @endif value="1">1 Year</option>
				               		<option @if($init['year_duration'] == 2) selected="selected" @endif value="2">2 Years</option>
				               		<option @if($init['year_duration'] == 3) selected="selected" @endif value="3">3 Years</option>
				               		<option @if($init['year_duration'] == 4) selected="selected" @endif value="4">4 Years</option>
				               </select>
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="owner_name" class="col-sm-3 control-label">Credit Person</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="owner_name" value="{{$init['owner_name']}}" id="owner_name" placeholder="Enter Person Name">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="interest_rate" class="col-sm-3 control-label">Interest Rate</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="interest_rate" value="{{$init['interest_rate']}}" id="interest_rate" placeholder="Enter Rate Percentage">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="admin_cost" class="col-sm-3 control-label">Admin Cost</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="admin_cost" value="{{$init['admin_cost']}}" id="admin_cost" placeholder="Enter Admin Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="insurance_cost" class="col-sm-3 control-label">Insurance Cost</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="insurance_cost" value="{{$init['insurance_cost']}}" id="insurance_cost" placeholder="Enter Insurance Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="installment_cost" class="col-sm-3 control-label">Installment Cost</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="installment_cost" value="{{$init['installment_cost']}}" id="installment_cost" placeholder="Enter Installment Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="other_cost" class="col-sm-3 control-label">Other Cost</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="other_cost" value="{{$init['other_cost']}}" id="other_cost" placeholder="Enter Other Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="total_down_payment" class="col-sm-3 control-label">Total Down Payment</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="total_down_payment" value="{{$init['total_down_payment']}}" id="total_down_payment" placeholder="Enter Total Down Payment Amount">
			               </div>
			            </div>  		
		            </div>
	          	</div>
	        </div>