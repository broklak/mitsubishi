<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Pemesan</h3>
	</div>
	<div class="box-body">

					<div class="form-group">
	                  <label for="customer_phone" class="col-sm-3 control-label">Nomor Handphone</label>
	                  <div class="col-sm-9">
	                  	<input type="text" name="customer_phone" id="customer_phone" value="{{$init['customer_phone']}}" class="form-control">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="id_type" class="col-sm-3 control-label">Jenis Identitas</label>
	                  <div class="col-sm-9">
	                  	<label class="radio-inline"><input @if($init['id_type'] == 1) checked="checked" @endif type="radio" value="1" name="id_type">KTP</label>
	                  	<label class="radio-inline"><input @if($init['id_type'] == 2) checked="checked" @endif type="radio" value="2" name="id_type">SIM</label>
	                  	<label class="radio-inline"><input @if($init['id_type'] == 3) checked="checked" @endif type="radio" value="3" name="id_type">Passport</label>
	                  </div>
	                </div>

	                <div class="form-group">
	                	@if(isset($init['id_image']))
	                	<img style="width:150px;height:100px" src="{{ asset('images') . '/customer/' . $init['folder_id_image'] . '/' . $init['id_image'] }}" />
	                	@endif
	                	<img style="width:150px;height:100px;display:none" id="id_image" />
	                  <label for="id_image" class="col-sm-3 control-label">Foto Tanda Identitas</label>
	                  <div class="col-sm-9">
	                    <input type="file" class="form-control" name="id_image" id="ids_image">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="id_number" class="col-sm-3 control-label">Nomor Identitas</label>
	                  <div class="col-sm-9">
	                  	<input type="text" class="form-control" name="id_number" value="{{$init['id_number']}}" id="id_number" placeholder="Nomor Tanda Pengenal">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="customer_name" class="col-sm-3 control-label">Nama Pemesan</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_first_name" value="{{$init['customer_first_name']}}" id="customer_name" placeholder="Nama Pemesan">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="customer_address" class="col-sm-3 control-label">Alamat</label>
	                  <div class="col-sm-9">
	                  	<textarea name="customer_address" rows="3" id="address" class="form-control">{{$init['customer_address']}}</textarea>
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="customer_phone_home" class="col-sm-3 control-label">Nomor Telepon</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_phone_home" value="{{$init['customer_phone_home']}}" id="customer_phone_home" placeholder="Nomor Telepon Rumah / Kantor">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="customer_business" class="col-sm-3 control-label">Jenis Usaha</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_business" value="{{$init['customer_business']}}" id="customer_business" placeholder="Jenis Usaha">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="customer_npwp" class="col-sm-3 control-label">NPWP</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_npwp" value="{{$init['customer_npwp']}}" id="customer_npwp" placeholder="Nomor NPWP">
	                  </div>
	                </div>

	                <div class="form-group">
	                	@if(isset($init['npwp_image']))
	                	<img style="width:150px;height:100px" src="{{ asset('images') . '/npwp/' . $init['npwp_image'] }}" />
	                	@endif
	                  <label for="npwp_image" class="col-sm-3 control-label">Foto NPWP</label>
	                  <div class="col-sm-9">
	                    <input type="file" class="form-control" name="npwp_image" id="npwp_image">
	                  </div>
	                </div>
	</div>
</div>