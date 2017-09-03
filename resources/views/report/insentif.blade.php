@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <!-- /.box-header -->
            <div class="box-header">
              <form class="form-inline">
                <div class="form-group">
                  <label for="month">Month</label>
                  <select onchange="this.form.submit()" id="month" name="month" class="form-control">
                    @foreach(getMonths() as $key => $val)
                    <option @if($month == $key) selected @endif value="{{$key}}">{{$val}}</option>
                    @endforeach
                  </select>
                </div>

                <div style="margin-left:15px" class="form-group">
                  <label for="year">Years</label>
                  <select onchange="this.form.submit()" id="year" name="year" class="form-control">
                    @foreach(getPrevYears() as $key => $val)
                    <option @if($year == $val) selected @endif>{{$val}}</option>
                    @endforeach
                  </select>
                </div>
              </form>
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
                <td>{{moneyFormat($val['imbalan'])}}</td>
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