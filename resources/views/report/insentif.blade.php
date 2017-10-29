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
                <a class="btn btn-primary" href="{{route('report.excel.insentif')}}?start={{$startTime}}&end={{$endTime}}">Export to CSV</a>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Fleet Sales</th>
                  <th>Non Fleet Sales</th>
                  <th>Total Car Sales</th>
                  <th>Insentive</th>
                  <th>Salary</th>
                  <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{App\User::getName($key)}}</td>
                <td>{{$val['fleet']}}</td>
                <td>{{$val['non_fleet']}}</td>
                <td>{{$val['sales']}}</td>
                <td>{{moneyFormat($val['total_insentif'])}}</td>
                <td>{{moneyFormat($val['total_imbalan'])}}</td>
                <td>{{moneyFormat($val['sales_accepted'])}}</td>
                </tr>
                @endforeach
              </table>
            </div>
            <!-- /.box-body -->
          </div>
	</div>
</section>

@endsection