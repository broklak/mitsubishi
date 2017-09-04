@extends('layout.main')

@section('title', 'Home')

@section('content')
<!-- Main content -->
    <section class="content">
      <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">KTP</a></li>
              <li><a href="#tab_2" data-toggle="tab">SIM</a></li>
              <li><a href="#tab_3" data-toggle="tab">Passport</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                  @foreach($ktp as $key => $val)
                    <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/ktp/' . $val->image }}" />
                  @endforeach
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                  @foreach($sim as $key => $val)
                    <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/sim/' . $val->image }}" />
                  @endforeach
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                  @foreach($passport as $key => $val)
                    <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/passport/' . $val->image }}" />
                  @endforeach
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
        </div>
    </section>
@endsection