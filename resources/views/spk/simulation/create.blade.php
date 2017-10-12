@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    <form class="form-horizontal" action="{{route("$page.store")}}" method="post" enctype="multipart/form-data">
    	@foreach($errors->all() as $message)
		   	<div style="margin: 20px 0" class="alert alert-error">
		        {{$message}}
		     </div>
		@endforeach
	    {{csrf_field()}}
	    <div class="col-md-12">
	    	<div class="col-md-12">
	    		<form>
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Simulasi Kredit</h3>
					</div>
		            <div class="box-body">

			            <div class="form-group">
			                <label for="date" class="col-sm-2 control-label">Leasing</label>
			                <div class="col-sm-10">
			                   <select name="leasing_id" class="form-control" id="leasing_id">
			                   		<option disabled="disabled" selected="selected" value="0">Pilih Leasing</option>
			                   		@foreach($leasing as $key => $val)
			                   		<option @if(old('leasing_id') == $val->id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
			                   		@endforeach
			                   </select>
			                   @foreach($leasing as $key => $val)
				               <input type="hidden" id="admin_cost_leasing_{{$val->id}}" value="{{$val->admin_cost}}" />
				               @endforeach
				               <input type="hidden" id="admin_cost_leasing_0" value="0" />
			                </div>
			             </div>

			           <!--  <div class="form-group">
			               <label for="car_type" class="col-sm-2 control-label">Tipe Mobil</label>
			               <div class="col-sm-10">
			               		<input type="text" class="form-control" name="type_name" value="{{old('type_name')}}" id="type_id" placeholder="Car Type">
			               		<input type="hidden" value="{{old('type_id')}}" id="type_id_real" name="type_id" />
			               </div>
			            </div> -->

			            <div class="form-group">
		                  <label for="customer_name" class="col-sm-2 control-label">Nama Pemesan</label>
		                  <div class="col-sm-10">
		                    <input type="text" class="form-control" name="customer_name" value="{{old('customer_name')}}" id="customer_name" placeholder="Enter Customer Name">
		                  </div>
		                </div>

			            <!--  <div class="form-group">
			               <label for="car_year" class="col-sm-2 control-label">Tahun Kendaraan</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="car_year" value="{{old('car_year')}}" id="car_year" placeholder="Enter Year">
			               </div>
			             </div> -->

			             <div class="form-group">
			               <label for="price" class="col-sm-2 control-label">Harga Mobil</label>
			               <div class="col-sm-10">
			                  <input type="text" onkeyup="formatMoney($(this))" class="form-control" name="total_sales_price" value="{{old('total_sales_price')}}" id="total_sales_price" placeholder="Enter Price">
			                  <input type="hidden" id="total_unpaid" name="total_unpaid" value="{{old('total_unpaid')}}">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="dp_percentage" class="col-sm-2 control-label">DP (%)</label>
			               <div class="col-sm-10">
			                  <input type="number" class="form-control" name="dp_percentage" value="{{old('dp_percentage')}}" id="dp_percentage" placeholder="Enter DP Percentage">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="dp_amount" class="col-sm-2 control-label">DP (IDR)</label>
			               <div class="col-sm-10">
			                  <input type="text" onkeyup="formatMoney($(this))" class="form-control" name="dp_amount" value="{{old('dp_amount')}}" id="dp_amount" placeholder="Enter DP Amount">
			               </div>
			            </div>

			            <div class="form-group">
			                <label for="date" class="col-sm-2 control-label">Lama Kredit</label>
			                <div class="col-sm-10">
			                   <select name="duration" class="form-control" id="credit_duration">
			                   		<option disabled="disabled" selected="selected" value="0">Pilih Bulan</option>
			                   		@foreach($months as $key => $val)
			                   		<option @if(old('duration') == $val->months) selected @endif value="{{$val->months}}">{{$val->months}} Months</option>
			                   		@endforeach
			                   </select>
			                </div>
			             </div>

			             <div class="col-md-12" style="text-align:center">
				       		<a onclick="getCalculation()" style="cursor:pointer" style="width:100%" class="btn btn-primary">HITUNG</a>
				       </div>

		            </div>
		        </div>

		        <div class="box box-info" id="calculation" style="display:none">
					<div class="box-header with-border">
						<h3 class="box-title">Perhitungan</h3>
					</div>
		            <div class="box-body">

		            	<div class="form-group">
			               <label for="admin_cost" class="col-sm-2 control-label">Biaya Admininstrasi</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="admin_cost" value="{{old('admin_cost')}}" id="admin_cost">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="interest_rate" class="col-sm-2 control-label">Suku Bunga</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="interest_rate" value="{{old('interest_rate')}}" id="interest_rate">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="installment_cost" class="col-sm-2 control-label">Cicilan Perbulan</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="installment_cost" value="{{old('installment_cost')}}" id="installment_cost" readonly>
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="insurance_cost" class="col-sm-2 control-label">Biaya Asuransi</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="insurance_cost" value="{{old('insurance_cost')}}" id="insurance_cost">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="other_cost" class="col-sm-2 control-label">Biaya Lain Lain</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="other_cost" value="{{old('other_cost')}}" id="other_cost">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="total_dp" class="col-sm-2 control-label">TDP</label>
			               <div class="col-sm-10">
			                  <input type="text" class="form-control" name="total_dp" value="{{old('total_dp')}}" id="total_down_payment" readonly>
			               </div>
			            </div>

		            </div>
		        </div>
		        <div class="col-md-12">
				    <button type="submit" style="width:100%" class="btn btn-primary">SIMPAN SIMULASI</button>
				</div>
		        </form>
	        </div>

        </div>
        <div style="clear:both"></div>
    </form>
    </section>

@endsection