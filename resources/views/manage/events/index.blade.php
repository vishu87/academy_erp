@extends('layout')

@section('content')
<div class="main fixed-side" ng-controller="events_controller" ng-init="getList()">

	<div class="sidebar">

		<div class="filters">
			<div class="table-div">
				<div class="filter-label">Search</div>
				<div class="text-right">
					<button class="btn btn-primary" ng-click="getList()">Search</button>
				</div>
			</div>

			<div class="form-group">
				<label>Name</label>
				<input type="text" ng-model="filter.name" ng-required="!filter.start_date" class="form-control">
			</div>	

			<div class="form-group">
				<label>Start Date</label>
				<input type="text" ng-model="filter.start_date" ng-required="!filter.name" class="form-control datepicker" autocomplete="off">
			</div>	

			<div class="form-group">
				<label>End Date</label>
				<input type="text" ng-model="filter.end_date" ng-required="filter.start_date" class="form-control datepicker" autocomplete="off">
			</div>	

		</div>

	</div>

	<div class="content">
		
		<div class="portlet">
			<div class="portlet-head">
				<div class="row">

					<div class="col-md-6">
						<ul class="menu">
							<li class="active">
								<a href="#">Events List</a>
							</li>
						</ul>
					</div>
					<div class="col-md-6 text-right">
						<a href="{{url('/events/add/0')}}" class="btn btn-primary">Add</a>
					</div>

				</div>
			</div>

			<div class="portlet-body ng-cloak">
				
				<div class="table-cont">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>SN</th>
								<th>Code</th>
								<th>Name</th>
								<th>Description</th>
								<th>Latitude</th>
								<th>Longitude</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>#</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="event in events track by $index">
								<td>@{{$index+1}}</td>
								<td>@{{event.code}}</td>
								<td>@{{event.name}}</td>
								<td>@{{event.description|limitTo:100}} ...</td>
								<td>@{{event.latitude}}</td>
								<td>@{{event.longitude}}</td>
								<td>@{{event.start_date}}</td>
								<td>@{{event.end_date}}</td>
								<td>
									<button class="btn btn-info btn-sm" ng-click="showEvent(event)">View</button>
									<a class="btn btn-warning btn-sm" href="{{url('events/add/')}}/@{{event.id}}">Edit</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>
		<div ng-show="loading">Loading ...</div>
		@include('manage.events.events_modal')
	</div>

</div>

@endsection

