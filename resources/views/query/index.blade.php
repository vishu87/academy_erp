@extends('layout')

@section('content')

	<div class="page-header row">
		<div class="col-6">
			<h3>Query</h3>
		</div>
		<div class="col-6">
			<div class="text-right">
			</div>
		</div>
	</div>

	<div class="portlet">
		<div class="portlet-body">
			<div class="filters">
	            {{ Form::open(array('url' => '/save-query', 'method'=>'POST',"autocomplete"=>"off","class"=>"form-horizontal")) }}
	              <div class="row">
	                  <div class="col-md-12 form-group">
	                    <label class="label-control">Add Query</label>
	                    {{Form::textarea('db_query','',["class"=>"form-control", "required"=>true])}}
	                  </div>
	              </div>
	              <div class="row">
	                  <div class="col-md-12">
	                    <button type="submit" class="btn btn-primary">Submit</button>
	                  </div>
	              </div>
	            {{Form::close()}}
	          </div>
			</div>
	</div>


@endsection

