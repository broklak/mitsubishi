@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            @if(!$approval)
            <div class="box-header">
              <a href="{{route($page.'.create')}}" class="btn btn-info">Create {{ucwords(str_replace('-',' ', $page))}}</a>
            </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>SPK Number</th>
                  <th>Customer</th>
                  <th>Car</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{$val->spk_code}}</td>
                <td>{{\App\Models\Customer::getName($val->customer_id)}}</td>
                <td>{{\App\Models\CarModel::getName($val->model_id).' '.\App\Models\CarType::getName($val->type_id)}}</td>
                <td>{!!setActivationStatus($val->status)!!}</td>
                <td>
                	<div class="btn-group">
	                  <button type="button" class="btn btn-info">Action</button>
	                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                  <ul class="dropdown-menu" role="menu">
                      @if(!$approval)
                      <li><a href="{{ route($page.'.edit', ['id' => $val->id]) }}">Edit</a></li>
                      @endif
                      <li><a href="{{ route($page.'.show', ['id' => $val->id]) }}">Show</a></li>
                      @if(!$approval)
	                    <li class="divider"></li>
	                    <li>
	                    	<form class="deleteForm" method="post" action="{{route("$page.destroy", ['id' => $val->id])}}">
	                    		{{csrf_field()}}
	                    		<button onclick="return confirm('You will delete this {{$page}}, continue')" type="submit">Delete</button>
	                    		{{ method_field('DELETE') }}
	                    	</form>
	                    </li>
                      @endif
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