			<!-- <div class="col-md-6"> -->
	          	<div class="box box-info">
	          		<div class="box-header with-border">
						<h3 class="box-title">Karoseri Data</h3>
					</div>
		          	<div class="box-body">
		        		<div class="form-group">
			               <label for="karoseri" class="col-sm-3 control-label">Karoseri</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="karoseri" value="{{$init['karoseri']}}" id="karoseri" placeholder="Karoseri">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="karoseri_type" class="col-sm-3 control-label">Karoseri Type</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="karoseri_type" value="{{$init['karoseri_type']}}" id="karoseri_type" placeholder="Karoseri Type">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="karoseri_spec" class="col-sm-3 control-label">Spesifikasi</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" name="karoseri_spec" value="{{$init['karoseri_spec']}}" id="karoseri_spec" placeholder="Spesifikasi">
			               </div>
			            </div>

			            <div class="form-group">
			               <label for="karoseri_price" class="col-sm-3 control-label">Harga</label>
			               <div class="col-sm-9">
			                  <input type="text" class="form-control" onkeyup="formatMoney($(this))" name="karoseri_price" value="{{$init['karoseri_price']}}" id="karoseri_price" placeholder="Harga">
			               </div>
			            </div>
			        </div>
	          	</div>
	          <!-- </div> -->