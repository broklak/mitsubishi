@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            @if(!$approval)
            <div class="box-header">
              <div class="col-md-3">
                @permission('create.spk')
                  <a href="{{route($page.'.create')}}" class="btn btn-info">Create {{ucwords(str_replace('-',' ', $page))}}</a>
                @endpermission
              </div>

              <div class="col-md-9">
                <form class="form-inline" style="float: right">
                  <div class="form-group">
                    <label for="query">Search SPK:</label>
                    <input type="text" class="form-control" id="query" value="{{$query}}" name="query" placeholder="Search by SPK Number" >
                  </div>
                  <button type="submit" class="btn btn-default">Search</button>
                </form>
              </div>

            </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>SPK Number</th>
                  <th>Sales Name</th>
                  <th>Date</th>
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
                <td>{{App\User::getName($val->created_by)}}</td>
                <td>{{date('j F Y', strtotime($val->date))}}</td>
                <td>{{$val->customer_name}}</td>
                <td>{{(isset($val->type_name)) ? $val->model_name . ' ' . $val->type_name : $val->type_others}}</td>
                <td>{!! App\Models\OrderApproval::getLabelStatus($val) !!}</td>
                <td>
                	<div class="btn-group">
	                  <button type="button" class="btn btn-info">Action</button>
	                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                  <ul class="dropdown-menu" role="menu">
                      @permission('update.spk')
                        @if(App\Models\OrderApproval::canEdit(App\Models\OrderApproval::getLabelStatus($val)))
                          <li><a href="{{ route($page.'.edit', ['id' => $val->id]) }}">Edit</a></li>
                        @endif
                      @endpermission
                      <li><a href="{{ route($page.'.show', ['id' => $val->id]) }}">Show</a></li>
                      @if(!$approval)
	                    <li class="divider"></li>
                        @permission('delete.spk')
    	                    <li>
    	                    	<form class="deleteForm" method="post" action="{{route("$page.destroy", ['id' => $val->id])}}">
    	                    		{{csrf_field()}}
    	                    		<button onclick="return confirm('You will delete this {{$page}}, continue')" type="submit">Delete</button>
    	                    		{{ method_field('DELETE') }}
    	                    	</form>
    	                    </li>
                        @endpermission
                      @endif
	                  </ul>
                	</div>
                </td>
                </tr>
                @endforeach
              </table>
              {{$result->links()}}
            </div>
            <!-- /.box-body -->
          </div>
	</div>
</section>

@endsection