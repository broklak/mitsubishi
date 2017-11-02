@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <div class="box-header">               
              @permission('create.user')
                <a href="{{route($page.'.create')}}" class="btn btn-info">Create {{ucwords(str_replace('-',' ', $page))}}</a>
              @endpermission
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Join Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{$val->first_name . ' ' . $val->last_name}}</td>
                <td>{{$val->username}}</td>
                <td>{{$val->email}}</td>
                <td>{{App\RoleUser::roleWordList($val->id)}}</td>
                <td>{{dateHumanFormat($val->start_work)}}</td>
                <td>{!!getUserValidityStatus($val->valid_login)!!}</td>
                <td>
                	<div class="btn-group">
	                  <button type="button" class="btn btn-info">Action</button>
	                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                  <ul class="dropdown-menu" role="menu">
                      @permission('update.user')
                        @if($val->username != 'admin')
    	                    <li><a href="{{ route($page.'.edit', ['id' => $val->id]) }}">Edit</a></li>
    	                    @if(Auth::user()->checkLoginValidity($val->valid_login))
    	                    <li><a href="{{ route($page.'.change-status', ['id' => $val->id, 'status' => 0]) }}">Suspend User</a></li>
    	                    @else
    	                    <li><a href="{{ route($page.'.change-status', ['id' => $val->id, 'status' => 1]) }}">Activate User</a></li>
    	                    @endif
                        @endif
  	                    <li class="divider"></li>
                      @endpermission
                      @permission('delete.user')
                        @if($val->username != 'admin')
    	                    <li>
    	                    	<form class="deleteForm" method="post" action="{{route("$page.destroy", ['id' => $val->id])}}">
    	                    		{{csrf_field()}}
    	                    		<button onclick="return confirm('You will delete this {{$page}}, continue')" type="submit">Delete</button>
    	                    		{{ method_field('DELETE') }}
    	                    	</form>
    	                    </li>
                        @endif
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