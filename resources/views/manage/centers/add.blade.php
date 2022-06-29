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
		<div class="row">
			<div class="col-md-8">
				<form ng-submit="onSubmit()" novalidate>
					@include("manage.centers.basics")
					<!-- @include("manage.centers.groups") 
					@include("manage.centers.center_images")
					@include("manage.centers.contact_persons") -->
				</form>
			</div>
			<div class="col-md-4">
				@include("manage.centers.center_images")
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<form ng-submit="onSubmit()" novalidate>
					@include("manage.centers.groups") 
				</form>
			</div>
		</div>
	</div>

	@include("manage.centers.modals")
</div>


@endsection


