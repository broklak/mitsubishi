				<!-- <div class="col-md-6"> -->
	          	<div class="box box-info">
		          	<div class="box-header with-border">
						<h3 class="box-title">Car Data</h3>
					</div>
		          	<div class="box-body">
		        		<div class="form-group">
			               <label for="stnk_name" class="col-sm-3 control-label">STNK Name</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="stnk_name" value="{{$init['stnk_name']}}" id="stnk_name" placeholder="Car Owner Name">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="stnk_address" class="col-sm-3 control-label">STNK Address</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="stnk_address" value="{{$init['stnk_address']}}" id="stnk_name" placeholder="Car Owner Address">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="faktur_conf" class="col-sm-3 control-label">Faktur</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="faktur_conf" value="{{$init['faktur_conf']}}" id="faktur_conf" placeholder="Faktur Confirmation">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="car_type" class="col-sm-3 control-label">Car Type</label>
			               <div class="col-sm-9">
			               		<input type="text" class="form-control" name="type_name" value="{{$init['type_name']}}" id="type_id" placeholder="Car Type">
			               		<input type="hidden" value="{{$init['type_id']}}" id="type_id_real" name="type_id" />
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="color" class="col-sm-3 control-label">Car Color</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="color" value="{{$init['color']}}" id="color" placeholder="Color">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="qty" class="col-sm-3 control-label">Total Unit</label>
			               <div class="col-sm-9">
			                  <input type="number" class="form-control" name="qty" value="{{$init['qty']}}" id="qty" placeholder="Total Unit">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="car_year" class="col-sm-3 control-label">Car Built Year</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="car_year" value="{{$init['car_year']}}" id="car_year" placeholder="Year">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="plat" class="col-sm-3 control-label">Plat Type</label>
			               <div class="col-sm-9">
			               		<select name="plat" class="form-control">
			               			<option @if($init['car_year'] == 1) selected="selected" @endif value="1">Hitam</option>
			               			<option @if($init['car_year'] == 2) selected="selected" @endif value="2">Kuning</option>
			               			<option @if($init['car_year'] == 3) selected="selected" @endif value="3">Merah</option>
			               		</select>
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="bbn_type" class="col-sm-3 control-label">BBN Type</label>
			               <div class="col-sm-9">
			                  	<select class="form-control" name="bbn_type">
			               			<option disabled="disabled" selected="selected">Choose BBN Type</option>
			               			@foreach($bbn as $key => $val)
			               			<option @if($init['bbn_type'] == $val->id) selected="selected" @endif value="{{$val->id}}">{{$val->name}}</option>
			               			@endforeach
			               			<option @if($init['bbn_type'] == 1000) selected="selected" @endif value="1000">Others</option>
			               		</select>
			               </div>
			            </div>
			        </div>
	          	</div>
	          <!-- </div> -->