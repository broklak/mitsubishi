@extends('layout.main')

@section('title', 'Home')

@section('content')
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-12">
			<div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Create {{ucwords(str_replace('-',' ', $page))}}</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            @foreach($errors->all() as $message)
		            <div style="margin: 20px 0" class="alert alert-error">
		                {{$message}}
		            </div>
		        @endforeach
	            <form class="form-horizontal" action="{{route("$page.update", ['id' => $row->id])}}" method="post">
	            	{{csrf_field()}}
	              <div class="box-body">
	                <div class="form-group">
	                  <label for="name" class="col-sm-2 control-label">Name</label>
	                  <div class="col-sm-10">
	                    <input type="text" class="form-control" name="name" value="{{$row->display_name}}" id="name" placeholder="Name">
	                  </div>
	                </div>

	                <div class="form-group">
	                  <label for="name" class="col-sm-2 control-label">Permissions</label>
	                  <div class="col-sm-10">
	                    	<div id="tree-role">
						        <div>
						            <ul>
						            	@foreach($permissions as $key => $val)
						                <li><input id="{{$key}}" type="checkbox"><label style="margin-left:7px" for="{{$key}}">{{$key}}</label>
						                	@foreach($val as $keyChild => $valChild)
						                    <ul>
						                        <li><input id="{{$keyChild}}" @if(in_array($keyChild, $validPermission)) checked @endif name="permission[]" value="{{$keyChild}}" type="checkbox"><label style="margin-left:10px" for="{{$keyChild}}">{{$valChild}}</span></label>
						                    </ul>
						                    @endforeach
						                 </li>
						                 @endforeach
						            </ul>
						        </div>
						    </div>
	                  </div>
	                </div>

	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	                <button type="submit" class="btn btn-info pull-right">Submit</button>
	              </div>
	              <!-- /.box-footer -->
	              {{ method_field('PUT') }}
	            </form>
	          </div>
          </div>
    </section>

@endsection