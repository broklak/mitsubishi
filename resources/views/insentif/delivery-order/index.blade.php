@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>DO Code</th>
                  <th>Type</th>
                  <th>Sales Name</th>
                  <th>SPK Code</th>
                  <th>Date</th>
                  <th>Total Sales Price</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{$val->do_code}}</td>
                <td>{{App\Models\DeliveryOrder::getType($val->is_fleet)}}</td>
                <td>{{App\User::getName($val->created_by)}}</td>
                <td>{{$val->spk_doc_code}}</td>
                <td>{{date('j F Y', strtotime($val->do_date))}}</td>
                <td>{{moneyFormat($val->total_sales_price)}}</td>
                <td>
                	<div class="btn-group">
	                  <button type="button" class="btn btn-info">Action</button>
	                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                  <ul class="dropdown-menu" role="menu">
	                    <li><a href="{{ route('delivery-order.show', ['id' => $val->id]) }}">View</a></li>
                      @permission('update.do')
                        <li class="divider"></li>
                        <li><a href="{{ route('delivery-order.change-type', ['id' => $val->id, 'type' => 1]) }}">Set Fleet</a></li>
                        <li><a href="{{ route('delivery-order.change-type', ['id' => $val->id, 'type' => 2]) }}">Set Not Fleet</a></li>
                      @endpermission
	                  </ul>
                	</div>
                </td>
                </tr>
                @endforeach
              </table>
            </div>
            <!-- /.box-body -->
          </div>
	</div>
</section>

@endsection