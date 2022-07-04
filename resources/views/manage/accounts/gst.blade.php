@extends('layout')

@section('content')
<div class="" ng-controller="AcountsController" ng-init="init('pt-view')">

	<div class="page-header row">
		<div class="col-md-6">
			<h3>Tax Settings</h3>
		</div>
	</div>
		
	<div class="portlet">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6 col-6">
					<ul class="menu">
						<li class="active mob-hi">
							<a href="#">List</a>
						</li>
					</ul>
				</div>

				<div class="col-md-6 col-6 text-right">
					<button class="btn btn-primary" ng-click="addAcount()"><i class="icons icon-plus"></i> Add New</button>
				</div>

			</div>
		</div>

		<div class="portlet-body ng-cloak">
			
			<div class="table-cont">
				<table class="table">
					<thead>
						<tr class="">
							<th>SN</th>
							<th>Entity Name</th>
							<th>State Name</th>
							<th>GST No</th>
							<th>Registered Office</th>
							<th>Contact Person</th>
							<th>Default</th>
							<th class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="list in listData track by $index">
							<td>@{{$index+1}}</td>
							<td>@{{list.name}}</td>
							<td>@{{list.state_name}}</td>
							<td>@{{list.gst_id}}</td>
							<td>@{{list.registered_office}}</td>
							<td>@{{list.contact_person}}</td>
							<td>@{{(list.defaults == 1) ? "Yes" : "No"}}</td>
							<td class="text-right">
								<button class="btn-light btn btn-sm" ng-click="edit(list)">Update</button>
								<button class="btn btn-danger btn-sm" ng-click="delete(list.id)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>
	
	@include("manage.accounts.modals")

</div>
@endsection