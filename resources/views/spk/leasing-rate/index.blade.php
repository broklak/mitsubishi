@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <div class="box-header">
              @permission('create.leasing.formula')
                <a href="{{route($page.'.create')}}" class="btn btn-info">Create {{ucwords(str_replace('-',' ', $page))}}</a>
              @endpermission
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>Leasing</th>
                  <th>Car</th>
                  <th>Areas</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key => $val)
                <tr>
                <td>{{\App\Models\Leasing::getName($val->leasing_id)}}</td>
                <td>{{\App\Models\CarModel::getName($val->car_model_id)}} - {{\App\Models\CarType::getName($val->car_type_id)}}</td>
                <td>{{\App\Models\Area::getNameByFields($val->areas)}}</td>
                <td>{{date('j F Y', strtotime($val->start_date))}}</td>
                <td>{{date('j F Y', strtotime($val->end_date))}}</td>
                <td>
                	<div class="btn-group">
	                  <button type="button" class="btn btn-info">Action</button>
	                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                  <ul class="dropdown-menu" role="menu">
                      @permission('update.leasing.formula')
	                    <li><a href="{{ route($page.'.edit', ['id' => $val->id]) }}">Edit</a></li>
	                    <li class="divider"></li>
                      @endpermission
                      @permission('delete.leasing.formula')
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