@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post" enctype="multipart/form-data">
    	@foreach($errors->all() as $message)
		   	<div style="margin: 20px 0" class="alert alert-error">
		        {{$message}}
		     </div>
		@endforeach
	    {{csrf_field()}}
	    <div class="col-md-12">
	    	<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">SPK Data</h3>
					</div>
		            <div class="box-body">

		            	<div class="form-group">
			                <label for="spk_code" class="col-sm-3 control-label">SPK Number</label>
			                <div class="col-sm-9">
			                   <input type="text" class="form-control" name="spk_code" value="{{$row->spk_code}}" id="spk_code" readonly="readonly">
			                </div>
			            </div>

			            <div class="form-group">
			                <label for="spk_doc_code" class="col-sm-3 control-label">Control Number</label>
			                <div class="col-sm-9">
			                   <input type="text" class="form-control" name="spk_doc_code" value="{{$row->spk_doc_code}}" id="spk_doc_code" placeholder="Control Number">
			                </div>
			            </div>

			            <div class="form-group">
			                <label for="date" class="col-sm-3 control-label">Date</label>
			                <div class="col-sm-9">
			                   <input type="text" class="form-control datepicker" name="date" value="{{$row->date}}" id="date" placeholder="Order Date">
			                </div>
			             </div>

			             <div class="form-group">
			                <label for="date" class="col-sm-3 control-label">Dealer</label>
			                <div class="col-sm-9">
			                   <select name="dealer_id" class="form-control">
			                   		<option disabled="disabled" selected="selected" value="0">Choose Dealer</option>
			                   		@foreach($dealer as $key => $val)
			                   		<option @if($row->dealer_id == $val->dealer_id) selected @endif value="{{$val->dealer_id}}">{{\App\Models\Dealer::getName($val->dealer_id)}}</option>
			                   		@endforeach
			                   </select>
			                </div>
			             </div>
		            </div>
		        </div>
		        @include('spk.order.fields.customer')

			    @include('spk.order.fields.car')

			   	@include('spk.order.fields.karoseri')
	        </div>

	        @include('spk.order.fields.price')

	        @include('spk.order.fields.downpayment')	 

	        @include('spk.order.fields.leasing')

        </div>
       <div class="col-md-12">
       		<button type="submit" style="width:100%" class="btn btn-primary">UPDATE SPK</button>
       </div>
        <div style="clear:both"></div>
        {{ method_field('PUT') }}
    </form>
    </section>

@endsection