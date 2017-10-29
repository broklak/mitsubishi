@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <!-- /.box-header -->
            <div class="box-header">
              <form class="form-inline pull-left">

                <div class="form-group">
                  <label>Start</label>
                  <input type="text" name="start" value="{{$start}}" class="form-control datepicker">
                </div>

                <div class="form-group" style="margin-left:15px" >
                  <label>End</label>
                  <input type="text" name="end" value="{{$end}}" class="form-control datepicker">
                </div>

                <div class="form-group" style="margin-left:15px" >
                  <input type="submit" value="Search" class="btn btn-primary">
                </div>

              </form>
              <div class="pull-right">
                <a class="btn btn-primary" href="{{route('report.excel.order')}}?start={{$startTime}}&end={{$endTime}}">Export to CSV</a>
              </div>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>SPK Number</th>
                  <th>Sales Name</th>
                  <th>Date</th>
                  <th>Customer</th>
                  <th>Car</th>
                  <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{$val->spk_code}}</td>
                <td>{{App\User::getName($val->created_by)}}</td>
                <td>{{date('j F Y', strtotime($val->date))}}</td>
                <td>{{$val->customer_name}}</td>
                <td>{{($val->type_id == null) ? App\Models\CarModel::getName($val->model_id) . ' '.$val->type_others : App\Models\CarType::getFullName($val->type_id)}}</td>
                <td>{!! App\Models\OrderApproval::getLabelStatus($val) !!}</td>
                </tr>
                @endforeach
              </table>
            </div>
            <!-- /.box-body -->
          </div>
	</div>
</section>

@endsection