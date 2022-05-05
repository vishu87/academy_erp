@extends('layout')

@section('content')	
	<div class="main fixed-side" ng-controller="ClientsController" ng-init="init()">

		<div class="sidebar">

			<div class="filters">
				<div class="table-div">
					<div class="filter-label">Add</div>
					<div class="text-right">
						<button class="btn btn-primary" ng-click="submit(addClinet)" ng-hide="update">Submit</button>
						<button class="btn btn-primary" ng-click="updateData(addClinet)" ng-show="update">Update</button>
					</div>
				</div>

				<div class="form-group">
					<label>Client Name</label>
					<input type="text" class="form-control" ng-model="addClinet.name">
				</div>	

				<div class="form-group">
					<label>Email</label>
					<input type="text" class="form-control" ng-model="addClinet.email">
				</div>	

				<div class="form-group">
					<label>Phone</label>
					<input type="text" class="form-control" ng-model="addClinet.phone">
				</div>	

				<div class="form-group">
					<label>Address</label>
					<input type="text" class="form-control" ng-model="addClinet.address">
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
									<a href="#">Clients List</a>
								</li>
							</ul>
						</div>

					</div>
				</div>

				<div class="portlet-body ng-cloak">
					
					<div class="table-cont">
						<table class="table">
							<thead class="">
								<tr>
									<td>Sn</td>
									<td>Client Name</td>
									<td>Email</td>
									<td>Phone</td>
									<td>Address</td>
									<td>#</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="rec in clientsRecord track by $index">
									<td>@{{$index + 1}}</td>
									<td>@{{rec.name}}</td>
									<td>@{{rec.email}}</td>
									<td>@{{rec.phone}}</td>
									<td>@{{rec.address}}</td>
									<td>
										<button class="btn btn-primary btn-sm" ng-click="edit(rec)">Edit</button>
										<button class="btn btn-danger btn-sm" ng-click="delete(rec.id)">Delete<i class="icon-trash icons"></i></button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>

	</div>
	<!-- <div class="row container-fluid" ng-controller="ClientsController" ng-init="init()">
		<div class="col-sm-3 form-group">
			<label>Client Name</label>
			<input type="text" class="form-control" ng-model="addClinet.name">
		</div>
		<div class="col-sm-3 form-group">
			<label>Email</label>
			<input type="text" class="form-control" ng-model="addClinet.email">
		</div>
		<div class="col-sm-3 form-group">
			<label>Phone</label>
			<input type="text" class="form-control" ng-model="addClinet.phone">
		</div>
		<div class="col-sm-3 form-group">
			<label>Address</label>
			<input type="text" class="form-control" ng-model="addClinet.address">
		</div>
		<div class="col-sm-3 form-group">
			<button class="btn btn-primary" ng-click="submit(addClinet)" ng-hide="update">Submit</button>

			<button class="btn btn-primary" ng-click="updateData(addClinet)" ng-show="update">Update</button>
		</div>

		<table class="table table-hover ml-3">
			<thead class="bg-dark text-light">
				<tr>
					<td>Sn</td>
					<td>Client Name</td>
					<td>Email</td>
					<td>Phone</td>
					<td>Address</td>
					<td>#</td>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="rec in clientsRecord track by $index">
					<td>@{{$index + 1}}</td>
					<td>@{{rec.name}}</td>
					<td>@{{rec.email}}</td>
					<td>@{{rec.phone}}</td>
					<td>@{{rec.address}}</td>
					<td>
						<button class="btn btn-primary" ng-click="edit(rec)">Edit</button>
						<button class="btn btn-danger" ng-click="delete(rec.id)">Delete<i class="icon-trash icons"></i></button>
					</td>
				</tr>
			</tbody>
		</table>
	</div> -->
@endsection


@section('footer_scripts')
<script type="text/javascript" 
  src="{{url('assets/plugins/admin/scripts/core/clients/clients_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection