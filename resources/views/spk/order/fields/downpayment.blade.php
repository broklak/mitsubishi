<div class="col-md-6">
	          	<div class="box box-info">
	          		<div class="box-header with-border">
						<h3 class="box-title">Data Uang Jaminan</h3>
					</div>
	          		<div class="box-body">
		        		<div class="form-group">
			               <label for="down_payment_amount" class="col-sm-3 control-label">Uang Panjar</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="booking_fee" value="{{$init['booking_fee']}}" id="down_payment" placeholder="Enter Amount">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="down_payment_date" class="col-sm-3 control-label">Tanggal Pembayaran</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control datepicker" name="down_payment_date" value="{{$init['down_payment_date']}}" id="down_payment_date" placeholder="Select Payment Date">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="jaminan_cost_percentage" class="col-sm-3 control-label">% Uang Jaminan</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" maxlength="100" name="dp_percentage" value="{{$init['dp_percentage']}}" id="dp_percentage" placeholder="Enter DP Percentage">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="jaminan_cost_amount" class="col-sm-3 control-label">Uang Jaminan (Rp)</label>
			               <div class="col-sm-9">
			                  <input type="text" id="dp_amount" onkeyup="formatMoney($(this))" class="form-control" name="dp_amount" value="{{$init['dp_amount']}}" placeholder="Enter DP Amount">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="total_unpaid" class="col-sm-3 control-label">Sisa Pembayaran</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" readonly="readonly" name="total_unpaid" value="{{$init['total_unpaid']}}" id="total_unpaid" placeholder="Unpaid Amount">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="payment_method" class="col-sm-3 control-label">Cara Pembayaran</label>
			               <div class="col-sm-9">
				               <label class="radio-inline"><input @if($init['payment_method'] == 1) checked="checked" @endif type="radio" value="1" name="payment_method">Cash</label>
		                  	   <label class="radio-inline"><input @if($init['payment_method'] == 2) checked="checked" @endif type="radio" value="2" name="payment_method">Credit Leasing / Bank</label>
			               </div>
			            </div>  		
		            </div>
	          	</div>
	        </div>