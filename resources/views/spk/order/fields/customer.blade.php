<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Customer Data</h3>
	</div>
	<div class="box-body">

					<div class="form-group">
	                  <label for="customer_phone" class="col-sm-3 control-label">Phone Number</label>
	                  <div class="col-sm-9">
	                  	<div class="ui-widget">
	                  		<select id="combobox" style="background-color=#fff" class="form-control">
	                  			<option value=""></option>
	                  			@foreach($customer as $key => $val)
	                  				<option @if($init['customer_phone'] == $val->phone) selected @endif value="{{$val->id}}">{{$val->phone}}</option>
	                  			@endforeach
							  </select>
							  	@foreach($customer as $key => $val)
	                  				<input type="hidden" id="firstname-{{$val->id}}" value="{{$val->first_name}}" />
	                  				<input type="hidden" id="lastname-{{$val->id}}" value="{{$val->last_name}}" />
	                  				<input type="hidden" id="idnumber-{{$val->id}}" value="{{$val->id_number}}" />
	                  				<input type="hidden" id="address-{{$val->id}}" value="{{$val->address}}" />
	                  				<input type="hidden" id="npwp-{{$val->id}}" value="{{$val->npwp}}" />
	                  				<input type="hidden" id="idtype-{{$val->id}}" value="{{$val->id_type}}" />
	                  				@php 
	                  					if($val->id_type == 1) $folder = 'ktp';
	                  					elseif($val->id_type == 2) $folder = 'sim';
	                  					else $folder = 'passport';
	                  				@endphp
	                  				<input type="hidden" id="idimage-{{$val->id}}" value="{{ asset('images') . '/customer/' . $folder . '/' . $val->image }}" />
	                  			@endforeach
						</div>
	                  </div>
	                </div>

					<div class="form-group">
	                  <label for="customer_name" class="col-sm-3 control-label">First Name</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_name" value="{{$init['customer_name']}}" id="customer_name" placeholder="First Name">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="customer_last_name" class="col-sm-3 control-label">Last Name</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_last_name" value="{{$init['customer_last_name']}}" id="customer_last_name" placeholder="Last Name">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="id_type" class="col-sm-3 control-label">ID Type</label>
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
	                  <label for="id_image" class="col-sm-3 control-label">ID Image</label>
	                  <div class="col-sm-9">
	                    <input type="file" class="form-control" name="id_image" id="id_image">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="id_number" class="col-sm-3 control-label">ID Number</label>
	                  <div class="col-sm-9">
	                  	<input type="text" class="form-control" name="id_number" value="{{$init['id_number']}}" id="id_number" placeholder="ID Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="customer_address" class="col-sm-3 control-label">Address</label>
	                  <div class="col-sm-9">
	                  	<textarea name="customer_address" rows="3" id="address" class="form-control">{{$init['customer_address']}}</textarea>
	                  </div>
	                </div>

	                <!-- <div class="form-group">
	                  <label for="customer_phone" class="col-sm-3 control-label">Phone Number</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_phone" value="{{$init['customer_phone']}}" id="customer_phone" placeholder="Phone Number">
	                  </div>
	                </div> -->

	                <div class="form-group">
	                  <label for="customer_npwp" class="col-sm-3 control-label">NPWP</label>
	                  <div class="col-sm-9">
	                    <input type="text" class="form-control" name="customer_npwp" value="{{$init['customer_npwp']}}" id="customer_npwp" placeholder="NPWP Number">
	                  </div>
	                </div>

	                <div class="form-group">
	                	@if(isset($init['npwp_image']))
	                	<img style="width:150px;height:100px" src="{{ asset('images') . '/npwp/' . $init['npwp_image'] }}" />
	                	@endif
	                  <label for="npwp_image" class="col-sm-3 control-label">NPWP Image</label>
	                  <div class="col-sm-9">
	                    <input type="file" class="form-control" name="npwp_image" id="npwp_image">
	                  </div>
	                </div>
	</div>
</div>