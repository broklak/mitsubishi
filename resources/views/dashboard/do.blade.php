@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <!-- /.box-header -->
            <div class="box-header">
              <!-- <form class="form-inline">
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
              </form> -->
            </div>
            <div class="box-body">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h2 class="box-title">Total Car Sales and DO</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                  <div class="chart" id="do-chart" style="height: 300px;"></div>
                </div>
                <!-- /.box-body -->
              </div>

              <div class="box box-success">
                <div class="box-header with-border">
                  <h2 class="box-title">Status Active SPK</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                  <div class="chart" id="spk-chart" style="height: 300px;"></div>
                </div>
                <!-- /.box-body -->
              </div>
            </div>
            <!-- /.box-body -->
          </div>
	</div>
</section>

@endsection