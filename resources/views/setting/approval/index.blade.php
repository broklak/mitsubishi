@extends('layout.main')

@section('title', 'Home')

@section('content')

<section class="content">
	<div class="col-md-12">
		{!! session('displayMessage') !!}
		<div class="box">
            <div class="box-header">
              <a href="{{route($page.'.create')}}" class="btn btn-info">Add New Approver</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form class="form-horizontal" action="{{route("$page.change-level")}}" method="post">
              {{csrf_field()}}
                <table class="table table-bordered table-hover table-striped">
                  <thead>
                  <tr>
                    <th>Role</th>
                    <th>Approval level</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($result as $key => $val)
                  <tr>
                  <td>{{\App\Role::getName($val->job_position_id)}}</td>
                  <td>
                    <input style="width:30px;text-align:center" type="number" name="level[{{$val->id}}]" value="{{$val->level}}" />
                  </td>
                  <td>
                  	<div class="btn-group">
  	                  <a href="{{route("$page.delete", ['id' => $val->id])}}" onclick="return confirm('You will delete this approver, continue')" class="btn btn-danger">Remove</a>
                  	</div>
                  </td>
                  </tr>
                  @endforeach
                </table>
                <div class="box-footer">
                  <button type="submit" class="btn btn-info pull-right">Update Level</button>
                </div>
                {{ method_field('PUT') }}
              </form>
            </div>
            <!-- /.box-body -->
          </div>
	</div>
</section>

@endsection