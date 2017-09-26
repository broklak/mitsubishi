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
              <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>SPK Number</th>
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
                <td>{{date('j F Y', strtotime($val->date))}}</td>
                <td>{{$val->first_name . ' ' . $val->last_name}}</td>
                <td>{{$val->model_name.' '.$val->type_name}}</td>
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