@extends('layout')

@section('content')
<div class="ng-cloak" ng-controller="CenterController" ng-init="centerList()">

	<div class="page-header row">
		<div class="col-md-6">

			<h3>Centers</h3>
		</div>
		<div class="col-md-6 text-right">
			<button class="btn btn-primary" ng-click="addCenter();"><i class="icons icon-plus"></i> Add Center</button>
		</div>
	</div>
		
	<div class="portlet">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6">
					<ul class="menu">
						<li class="active">
							<a href="#">Active Centers</a>
						</li>
					</ul>
				</div>

				<div class="col-md-6 text-right">
					Filter by city
					<select class="form-control" style="display: inline-block; width: 100px;" ng-model="city_id" ng-change="centerList()">
						<option ng-value=0>Select</option>
						<option ng-repeat="city in cities" ng-value="city.id">@{{city.city_name}}</option>
					</select>
				</div>

			</div>
		</div>

		<div class="portlet-body ng-cloak">
			
			<div class="table-cont">

				<table class="table">
					<thead>
						<tr>
							<th>Sn</th>
							<th>Center Name</th>
							<th>City</th>
							<th>State</th>
							<th>Address</th>
							<th style="width: 120px;" class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="center in centers track by $index">
							<td>@{{$index+1}}</td>
							<td>@{{center.center_name}}</td>
							<td>@{{center.city_name}}</td>
							<td>@{{center.state_name}}</td>
							<td>@{{center.address}}</td>
							<td class="text-right">
								<a href="{{url('centers/edit/')}}/@{{center.id}}" class="btn btn-light btn-sm">Edit</a> 
								<button class="btn btn-danger btn-sm" ng-click="deleteCenter(center.id, $index)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
				
			</div>

		</div>
	</div>

	@include("manage.centers.modals")
</div>
@endsection


@section('footer_scripts')
  <script type="text/javascript" 
  src="{{url('assets/plugins/admin/scripts/core/center/center_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection