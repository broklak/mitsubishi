<div class="col-md-6">
	          	<div class="box box-info">
	          		<div class="box-header with-border">
						<h3 class="box-title">Price Data</h3>
					</div>
	          		<div class="box-body">
		          		<div class="form-group">
		                  <label for="price_type" class="col-sm-3 control-label">Price Type</label>
		                  <div class="col-sm-9">
		                  	<label class="radio-inline"><input @if($init['price_type'] == 1) checked="checked" @endif type="radio" value="1" name="price_type">Off The Road</label>
		                  	<label class="radio-inline"><input @if($init['price_type'] == 2) checked="checked" @endif type="radio" value="2" name="price_type">On The Road</label>
		                  	<input type="hidden" id="price_type" value="{{$init['price_type']}}" />
		                  </div>
		                </div>
		        		<div class="form-group" id="oftr-cont" style="display:{{($init['price_type'] == 1) ? 'block' : 'none'}}">
			               <label for="price_off" class="col-sm-3 control-label">Price</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="price_off" value="{{$init['price_off']}}" id="price_off" placeholder="Enter Price">
			               </div>
			            </div>
			            <div id="ontr-cont" style="display:{{($init['price_type'] == 2) ? 'block' : 'none'}}">
			            	<div class="form-group">
				               <label for="price_on" class="col-sm-3 control-label">Price</label>
				               <div class="col-sm-9">
				                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="price_on" value="{{$init['price_on']}}" id="price_on" placeholder="Enter Price">
				               </div>
				            </div>
				            <div class="form-group">
				               <label for="cost_surat" class="col-sm-3 control-label">STNK Cost</label>
				               <div class="col-sm-9">
				                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="cost_surat" value="{{$init['cost_surat']}}" id="cost_surat" placeholder="Enter Cost">
				               </div>
				            </div>  		
			            </div>
			            <div class="form-group">
			               <label for="discount" class="col-sm-3 control-label">Discount</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="discount" value="{{$init['discount']}}" id="discount" placeholder="Enter Discount">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="total_sales_price" class="col-sm-3 control-label">Total Sales</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="total_sales_price" value="{{$init['total_sales_price']}}" id="total_sales_price" placeholder="Enter Total Price">
			               </div>
			            </div>
		            </div>
	          	</div>
	        </div>