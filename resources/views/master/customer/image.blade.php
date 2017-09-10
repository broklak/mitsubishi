@extends('layout.main')

@section('title', 'Home')

@section('content')
<!-- Main content -->
    <section class="content">
      <div class="col-md-12">
          <a style="margin-bottom:15px" href="{{route($page.'.create')}}" class="btn btn-info">Create {{ucwords(str_replace('-',' ', $page))}}</a>
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">KTP</a></li>
              <li><a href="#tab_2" data-toggle="tab">SIM</a></li>
              <li><a href="#tab_3" data-toggle="tab">Passport</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Attachment</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($ktp as $key => $val)
                        <tr>
                          <td>
                            <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/ktp/' . $val->image }}" />
                          </td>
                          <td>
                            <a class="btn btn-primary" href="{{ route('customer.edit', ['id' => $val->id]) }}"">Edit Detail</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Attachment</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($sim as $key => $val)
                        <tr>
                          <td>
                            <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/ktp/' . $val->image }}" />
                          </td>
                          <td>
                            <a class="btn btn-primary" href="{{ route('customer.edit', ['id' => $val->id]) }}"">Edit Detail</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Attachment</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($passport as $key => $val)
                        <tr>
                          <td>
                            <img style="max-width:400px;display:block;margin:20px" src="{{ asset('images') . '/customer/ktp/' . $val->image }}" />
                          </td>
                          <td>
                            <a class="btn btn-primary" href="{{ route('customer.edit', ['id' => $val->id]) }}"">Edit Detail</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
        </div>
    </section>
@endsection