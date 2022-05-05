@extends('layout')

@section('content')

<div class="" ng-controller="CityController" ng-init="cityInit()">

	<div class="page-header row">
		<div class="col-6">
			<h3>Cities</h3>
		</div>
		<div class="col-6">
			<div class="text-right">
				<button class="btn btn-primary" ng-click="addCity()"><i class="icons icon-plus"></i> Add City</a>
			</div>
		</div>
	</div>

	<div class="portlet">
		<div class="portlet-body ng-cloak">
			<div class="table-responsive" ng-if="city_list.length > 0">
				<table class="table">
					<thead>
						<tr class="">
							<th>SN</th>
							<th>City name</th>
							<th>State name</th>
							<th class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="city in city_list track by $index">
							<td>@{{$index+1}}</td>
							<td>@{{city.city_name}}</td>
							<td>@{{city.state_name}}</td>
							<td class="text-right">
								<button class="btn-light btn btn-sm" ng-click="editCity(city)">Edit</button>
								<button class="btn-danger btn btn-sm" ng-click="deleteCity(city.id)">Mark Inactive</button>
							</td>
						</tr>
					</tbody>
		 		</table>
			</div>

			<div class="alert alert-warning mt-2" ng-if="city_list.length == 0">
      	No cities are available. Kindly add cities where your academy operates.
    	</div>

		</div>
	</div>

	@include("manage.cities.modals")
</div>

@endsection


@section('footer_scripts')
  <script type="text/javascript" 
  src="{{url('assets/plugins/admin/scripts/core/city/city_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection