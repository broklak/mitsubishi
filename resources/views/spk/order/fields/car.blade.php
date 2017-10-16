				<!-- <div class="col-md-6"> -->
	          	<div class="box box-info">
		          	<div class="box-header with-border">
						<h3 class="box-title">Data Mobil</h3>
					</div>
		          	<div class="box-body">
		        		<div class="form-group">
			               <label for="stnk_name" class="col-sm-3 control-label">Nama STNK</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="stnk_name" value="{{$init['stnk_name']}}" id="stnk_name" placeholder="Nama Pemilik Mobil">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="stnk_address" class="col-sm-3 control-label">Alamat STNK</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="stnk_address" value="{{$init['stnk_address']}}" id="stnk_name" placeholder="Alamat Pemilik Mobil">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="faktur_conf" class="col-sm-3 control-label">Konfirmasi Faktur</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="faktur_conf" value="{{$init['faktur_conf']}}" id="faktur_conf" placeholder="Konfirmasi Faktur">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="car_type" class="col-sm-3 control-label">Tipe</label>
			               <div class="col-sm-9">
			               		<input type="text" class="form-control" name="type_name" value="{{$init['type_name']}}" id="type_id" placeholder="Tipe Mobil">
			               		<input type="hidden" value="{{$init['type_id']}}" id="type_id_real" name="type_id" />
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="color" class="col-sm-3 control-label">Warna</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="color" value="{{$init['color']}}" id="color" placeholder="Warna">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="qty" class="col-sm-3 control-label">Quantity</label>
			               <div class="col-sm-9">
			                  <input type="number" class="form-control" name="qty" value="{{$init['qty']}}" id="qty" placeholder="Total Unit">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="car_year" class="col-sm-3 control-label">Tahun Kendaraan</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="car_year" value="{{$init['car_year']}}" id="car_year" placeholder="Tahun Kendaraan">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="plat" class="col-sm-3 control-label">Jenis Plat</label>
			               <div class="col-sm-9">
			               		<select name="plat" class="form-control">
			               			<option @if($init['car_year'] == 1) selected="selected" @endif value="1">Hitam</option>
			               			<option @if($init['car_year'] == 2) selected="selected" @endif value="2">Kuning</option>
			               			<option @if($init['car_year'] == 3) selected="selected" @endif value="3">Merah</option>
			               		</select>
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="bbn_type" class="col-sm-3 control-label">Jenis BBN</label>
			               <div class="col-sm-9">
			                  	<select class="form-control" name="bbn_type">
			               			<option disabled="disabled" selected="selected">Pilih Jenis BBN</option>
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