@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <div class="box-header">
              <a href="{{route('banner.create')}}" class="btn btn-info">Create New Banner</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>Banner Name</th>
                  <th>Image</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{$val->name}}</td>
                <td><img src="{{ asset('images') . '/banner/' . $val->file }}" style="width:100px;height:75px" /></td>
                <td>{!!\App\Helpers\GlobalHelper::setActivationStatus($val->status)!!}</td>
                <td>
                	<div class="btn-group">
	                  <button type="button" class="btn btn-info">Action</button>
	                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                  <ul class="dropdown-menu" role="menu">
	                    <li><a href="{{ route('banner.edit', ['id' => $val->id]) }}">Edit</a></li>
	                    @if($val->status == 1)
	                    <li><a href="{{ route('banner.change-status', ['id' => $val->id, 'status' => 0]) }}">Set Non Active</a></li>
	                    @else
	                    <li><a href="{{ route('banner.change-status', ['id' => $val->id, 'status' => 1]) }}">Set Active</a></li>
	                    @endif
	                    <li class="divider"></li>
	                    <li>
	                    	<form class="deleteForm" method="post" action="{{route("banner.destroy", ['id' => $val->id])}}">
	                    		{{csrf_field()}}
	                    		<button onclick="return confirm('You will delete this banner, continue')" type="submit">Delete</button>
	                    		{{ method_field('DELETE') }}
	                    	</form>
	                    </li>
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