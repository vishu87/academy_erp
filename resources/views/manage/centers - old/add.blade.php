@extends('layout')
 
@section('content')
 
<div ng-controller="CenterController" ng-init="init({{$center_id}})" class="ng-cloak">

	<div class="page-header row">
		<div class="col-md-6">
			<h3>Center - @{{center.center_name}}</h3>
		</div>
		<div class="col-md-6 text-right">
			<a href="{{url('centers')}}" class="btn btn-dark">Go Back</a>
		</div>
	</div>

	<div ng-show="loading" class="text-center">
		<div class="spinner-border" role="status">
		  <span class="sr-only">Loading...</span>
		</div>
	</div>
	<div ng-show="!loading">
		<form ng-submit="onSubmit()" novalidate>
				
			@include("manage.centers.basics")
			@include("manage.centers.groups") 
			@include("manage.centers.center_images")
			@include("manage.centers.contact_persons")

		</form>
	</div>

	

	@include("manage.centers.modals")
</div>


@endsection


@section('footer_scripts')
  <script type="text/javascript" 
  src="{{url('assets/plugins/admin/scripts/core/center/center_controller.js?v='.env('JS_VERSION')) }}" ></script>
@endsection