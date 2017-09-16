@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
  <div class="col-md-12">
    {!! session('displayMessage') !!}
    <div class="box">
            <div class="box-header">
              @permission('create.customer')
                <a href="{{route($page.'.create')}}" class="btn btn-info">Create {{ucwords(str_replace('-',' ', $page))}}</a>
              @endpermission
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>ID</th>
                  <th>ID Image</th>
                  <th>Phone</th>
                  <th>Address</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{$val->first_name . ' ' . $val->last_name}}</td>
                <td>{{$val->id_number}} ({{ getIDType($val->id_type) }})</td>
                <td>
                  <img style="width: 250px;height: 200px" src="{{asset('images/customer/') . '/' . strtolower(getIDType($val->id_type)) . '/' . $val->filename}}" />
                </td>
                <td>{{$val->phone}}</td>
                <td>{{$val->address}}</td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-info">Action</button>
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      @permission('update.customer')
                        <li><a href="{{ route($page.'.edit', ['id' => $val->id]) }}">Edit</a></li>
                        @if($val->status == 1)
                        <li><a href="{{ route($page.'.change-status', ['id' => $val->id, 'status' => 0]) }}">Set Non Active</a></li>
                        @else
                        <li><a href="{{ route($page.'.change-status', ['id' => $val->id, 'status' => 1]) }}">Set Active</a></li>
                        @endif
                        <li class="divider"></li>
                      @endpermission
                      @permission('delete.customer')
                        <li>
                          <form class="deleteForm" method="post" action="{{route("$page.destroy", ['id' => $val->id])}}">
                            {{csrf_field()}}
                            <button onclick="return confirm('You will delete this {{$page}}, continue')" type="submit">Delete</button>
                            {{ method_field('DELETE') }}
                          </form>
                        </li>
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