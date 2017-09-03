<div class="col-md-6">
	          	<div class="box box-info">
	          		<div class="box-header with-border">
						<h3 class="box-title">Down Payment Data</h3>
					</div>
	          		<div class="box-body">
		        		<div class="form-group">
			               <label for="down_payment_amount" class="col-sm-3 control-label">Booking Fee</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="down_payment_amount" value="{{$init['down_payment_amount']}}" id="down_payment" placeholder="Enter Amount">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="down_payment_date" class="col-sm-3 control-label">Payment Date</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control datepicker" name="down_payment_date" value="{{$init['down_payment_date']}}" id="down_payment_date" placeholder="Select Payment Date">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="jaminan_cost_percentage" class="col-sm-3 control-label">DP Percentage</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" maxlength="100" name="jaminan_cost_percentage" value="{{$init['jaminan_cost_percentage']}}" id="dp_percentage" placeholder="Enter DP Percentage">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="jaminan_cost_amount" class="col-sm-3 control-label">DP Amount</label>
			               <div class="col-sm-9">
			                  <input type="text" id="dp_amount" onkeyup="formatMoney($(this))" class="form-control" name="jaminan_cost_amount" value="{{$init['jaminan_cost_amount']}}" placeholder="Enter DP Amount">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="total_unpaid" class="col-sm-3 control-label">Total Unpaid</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" readonly="readonly" name="total_unpaid" value="{{$init['total_unpaid']}}" id="total_unpaid" placeholder="Unpaid Amount">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="payment_method" class="col-sm-3 control-label">Payment Method</label>
			               <div class="col-sm-9">
				               <label class="radio-inline"><input @if($init['payment_method'] == 1) checked="checked" @endif type="radio" value="1" name="payment_method">Cash</label>
		                  	   <label class="radio-inline"><input @if($init['payment_method'] == 2) checked="checked" @endif type="radio" value="2" name="payment_method">Credit Leasing / Bank</label>
			               </div>
			            </div>  		
		            </div>
	          	</div>
	        </div>