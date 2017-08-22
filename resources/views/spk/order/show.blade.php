@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post" enctype="multipart/form-data">
    	{!! session('displayMessage') !!}
	    {{csrf_field()}}
	    <div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border" style="text-align:center">
					<div class="pull-left">
						<h3 class="box-title">{{$row->spk_code}}</h3>
					</div>
					<div class="pull-right">
						<h3 class="box-title">Date : {{date('j F Y',strtotime($row->date))}}</h3>
					</div>
				</div>
				<div class="box-body">
					<div class="col-md-6">

			            <div class="form-group">
			                <label for="spk_doc_code" class="col-sm-4 control-label">Control Number</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$row->spk_doc_code}}</span>
			                   	</div>
			                </div>
			            </div>

			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Dealer</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{\App\Models\Dealer::getName($row->dealer_id)}}</span>
			                   	</div>
			                </div>
			             </div>
			             <hr />
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Customer Name</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['customer_name'].' '.$init['customer_last_name']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Identification</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['id_number'].' ('.getIDType($init['id_type']).')'}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Customer Address</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['customer_address']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Customer Phone</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['customer_phone']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Customer NPWP</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['customer_npwp']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">STNK Name</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['stnk_name']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">STNK Address</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['stnk_address']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Faktur Confirmation</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['faktur_conf']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <hr />
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Car Type</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{\App\Models\CarModel::getName($row->model_id)}} {{\App\Models\CarType::getName($row->type_id)}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Car Color</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['color']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Total Unit</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['qty']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Car Built Year</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['car_year']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">License Type</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{getPlatType($init['plat'])}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">BBN Type</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{\App\Models\Bbn::getName($row->bbn_type)}}</span>
			                   	</div>
			                </div>
			             </div>
			             <hr />
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Karoseri</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['karoseri']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Karoseri Type</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['karoseri_type']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Karoseri Spec</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['karoseri_spec']}}</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Karoseri Price</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{$init['karoseri_price']}}</span>
			                   	</div>
			                </div>
			             </div>
		            </div>
		            <div class="col-md-6">
		            	<div class="form-group">
			                <label for="date" class="col-sm-4 control-label">Price Type</label>
			                <div class="col-sm-8">
			                   <div class="spk-field">
			                   		<span>{{($init['price_type'] == 1) ? 'Off' : 'On'}} The Road</span>
			                   	</div>
			                </div>
			             </div>
			             <div class="form-group" style="display:{{($init['price_type'] == 1) ? 'block' : 'none'}}">
			             	<label for="price_off" class="col-sm-4 control-label">Price</label>
			             	<div class="col-sm-8">
			             		<div class="spk-field">
			                   		<span>{{$init['price_off']}}</span>
			                   	</div>
			             	</div>
			             </div>
			             <div style="display:{{($init['price_type'] == 2) ? 'block' : 'none'}}">
			             	<div class="form-group">
				                <label for="date" class="col-sm-4 control-label">Price</label>
				                <div class="col-sm-8">
				                   <div class="spk-field">
				                   		<span>{{$init['price_on']}}</span>
				                   	</div>
				                </div>
			             	</div>
			             	<div class="form-group">
				                <label for="date" class="col-sm-4 control-label">Cost Surat</label>
				                <div class="col-sm-8">
				                   <div class="spk-field">
				                   		<span>{{$init['cost_surat']}}</span>
				                   	</div>
				                </div>
			             	</div>
			             </div>
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">Discount</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{$init['discount']}}</span>
				                </div>
				            </div>
			             </div>
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">Total Sales</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{$init['total_sales_price']}}</span>
				                </div>
				            </div>
			             </div>
			             <hr />
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">Booking Fee</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{$init['down_payment_amount']}}</span>
				                </div>
				            </div>
			             </div>
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">Down Payment Date</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{$init['down_payment_date']}}</span>
				                </div>
				            </div>
			             </div>
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">DP Amount</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{$init['jaminan_cost_amount']}}</span>
				                </div>
				            </div>
			             </div>
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">DP Percentage</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{$init['jaminan_cost_percentage']}}</span>
				                </div>
				            </div>
			             </div>
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">Total Unpaid</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{$init['total_unpaid']}}</span>
				                </div>
				            </div>
			             </div>
			             <div class="form-group">
				            <label for="date" class="col-sm-4 control-label">Payment Method</label>
				            <div class="col-sm-8">
				                <div class="spk-field">
				                   	<span>{{($init['payment_method'] == 1) ? 'Cash' : 'Credit Leasing / Bank'}}</span>
				                </div>
				            </div>
			             </div>
			             <div style="display:{{($init['payment_method'] == 2) ? 'block' : 'none'}}">
			             	<hr />
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Leasing Name</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{\App\Models\Leasing::getName($init['leasing_id'])}}</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Credit Duration</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['year_duration']}} Years</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Credit Person</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['owner_name']}}</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Interest Rate</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['interest_rate']}}</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Admin Cost</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['admin_cost']}}</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Insurance Cost</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['insurance_cost']}}</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Installment Cost</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['installment_cost']}}</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Other Cost</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['other_cost']}}</span>
					                </div>
					            </div>
			             	</div>
			             	<div class="form-group">
					            <label for="date" class="col-sm-4 control-label">Total Down Payment</label>
					            <div class="col-sm-8">
					                <div class="spk-field">
					                   	<span>{{$init['total_down_payment']}}</span>
					                </div>
					            </div>
			             	</div>
			             </div>
		            </div>
				</div>
	       	</div>
	       	<div>
	       		<table class="table approver" style="border:1px solid #555">
	       			<thead>
	       				<th colspan="{{count($approver)}}" style="text-align:center;border:1px solid #555">Approver</th>
	       			</thead>
	       			<tbody>
	       				<tr>
	       					@foreach($approver as $key => $val)
	       					<td>{{\App\Models\JobPosition::getName($val->job_position_id)}}</td>
	       					@endforeach
	       				</tr>
	       				<tr class="sign" style="text-align:center;border:1px solid #555"> 
	       					@foreach($approver as $key => $val)
	       						@if($toApprove && $authId == $val->job_position_id)
	       						<td><a onclick="return confirm('You will approve this SPK, continue')" href="{{route('order.approve', ['id' => $row->id, 'level' => $val->level])}}" class="btn btn-primary">APPROVE SPK</a></td>
	       						@elseif(in_array($val->job_position_id, $approval))
	       						<td><i class="fa fa-check fa-4x"></i></td>
	       						@else
	       						<td>&nbsp;</td>
	       						@endif
	       					@endforeach
	       				</tr>
	       			</tbody>
	       		</table>
	       	</div>
        </div>
    </form>
    </section>

@endsection