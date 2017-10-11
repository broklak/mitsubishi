<div class="col-md-6" id="leasing-container" style="display:{{($init['payment_method'] == 2) ? 'block' : 'none'}}">
	          	<div class="box box-info">
	          		<div class="box-header with-border">
						<h3 class="box-title pull-left">Data Leasing</h3>
						<a class="btn btn-default pull-right" onclick="clearInterestFormula()">Reset Formula</a>
					</div>
	          		<div class="box-body">
		        		<div class="form-group">
			               <label for="leasing_id" class="col-sm-3 control-label">Leasing</label>
			               <div class="col-sm-9">
				               <select name="leasing_id" id="leasing_id" class="form-control">
				               		<option>Pilih Leasing</option>
				               		@foreach($leasing as $key => $val)
				               		<option @if($init['leasing_id'] == $val->id) selected="selected" @endif value="{{$val->id}}">{{$val->name}}</option>
				               		@endforeach
				               		<option @if($init['leasing_id'] == 0) selected="selected" @endif value="0">Leasing Lain</option>
				               </select>
				               @foreach($leasing as $key => $val)
				               <input type="hidden" id="admin_cost_leasing_{{$val->id}}" value="{{$val->admin_cost}}" />
				               @endforeach
				               <input type="hidden" id="admin_cost_leasing_0" value="{{$init['admin_cost']}}" />
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="year_duration" class="col-sm-3 control-label">Lama Kredit</label>
			               <div class="col-sm-9">
				               <select name="credit_duration" id="credit_duration" class="form-control">
				               		<option value="0">Pilih Jumlah Bulan Kredit</option>
				               		@foreach($months as $key => $val)
				               		<option @if($init['credit_duration'] == $val->months) selected="selected" @endif value="{{$val->months}}">{{$val->months}} Months</option>
				               		@endforeach
				               </select>
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="owner_name" class="col-sm-3 control-label">Kontrak atas Nama</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="credit_owner_name" value="{{$init['credit_owner_name']}}" id="owner_name" placeholder="Enter Person Name">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="interest_rate" class="col-sm-3 control-label">Suku Bunga</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="interest_rate" value="{{$init['interest_rate']}}" id="interest_rate" placeholder="Enter Rate Percentage">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="admin_cost" class="col-sm-3 control-label">Biaya Administrasi</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="admin_cost" value="{{$init['admin_cost']}}" id="admin_cost" placeholder="Enter Admin Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="insurance_cost" class="col-sm-3 control-label">Biaya Asuransi</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="insurance_cost" value="{{$init['insurance_cost']}}" id="insurance_cost" placeholder="Enter Insurance Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="installment_cost" class="col-sm-3 control-label">Cicilan Perbulan</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="installment_cost" value="{{$init['installment_cost']}}" id="installment_cost" readonly placeholder="Enter Installment Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="other_cost" class="col-sm-3 control-label">Biaya Lain</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="other_cost" value="{{$init['other_cost']}}" id="other_cost" placeholder="Enter Other Cost">
			               </div>
			            </div>
			            <div class="form-group">
			               <label for="total_down_payment" class="col-sm-3 control-label">TDP</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="total_down_payment" value="{{$init['total_down_payment']}}" id="total_down_payment" readonly placeholder="Enter Total Down Payment Amount">
			               </div>
			            </div>  		
		            </div>
	          	</div>
	        </div>