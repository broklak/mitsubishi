@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <!-- /.box-header -->
            <div class="box-header">
            </div>
            <div class="box-body">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h2 class="box-title">Total Car Sales and DO</h3>
                </div>
                <div class="box-body chart-responsive">
                  <div class="chart" id="do-chart" style="height: 300px;"></div>
                </div>
                <!-- /.box-body -->
              </div>

              <div class="box box-success">
                <div class="box-header with-border">
                  <h2 class="box-title">Status Active SPK</h3>
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