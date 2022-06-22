@extends('layout')

@section('content')

<div class="" ng-controller="ClientsController" ng-init="init()">

	<div class="page-header row">
		<div class="col-6">
			<h3>Clients</h3>
		</div>
		<div class="col-6">
			<div class="text-right">
				<button class="btn btn-primary" ng-click="addClient()"><i class="icons icon-plus"></i> Add</a>
			</div>
		</div>
	</div>

	<div class="portlet">
		<div class="portlet-body ng-cloak">
			<div class="table-responsive" ng-if="clientsRecord.length > 0">
				<table class="table">
					<thead>
						<tr>
		 					<th>Sn</th>
							<th>Code</th>
							<th>Client Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Address</th>
							<th class="text-right">#</th>
	 					</tr>
					</thead>
					<tbody>
						<tr ng-repeat="rec in clientsRecord track by $index">
							<td>@{{$index + 1}}</td>
							<td>@{{rec.code}}</td>
							<td>@{{rec.name}}</td>
							<td>@{{rec.email}}</td>
							<td>@{{rec.phone}}</td>
							<td>@{{rec.address}}</td>
							<td class="text-right">
								<button class="btn btn-sm btn-light" ng-click="edit(rec)">Edit</button>
								<button class="btn btn-sm btn-danger" ng-click="delete(rec.id, $index)">Delete</button>
							</td>
						</tr>
					</tbody>
		 		</table>
			</div>

			<div class="alert alert-warning mt-2" ng-if="clientsRecord.length == 0">
      	No clients are available. 
    	</div>

		</div>
	</div>

	@include("manage.clients.client_modal")
</div>

@endsection


@section('footer_scripts')
  <script type="text/javascript" 
  src="{{url('assets/plugins/admin/scripts/core/clients/clients_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection
