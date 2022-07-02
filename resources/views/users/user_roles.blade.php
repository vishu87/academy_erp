@extends('layout')

@section('content')

<div class="" ng-controller="Roles_controller" ng-init="roles_init()">

	<div class="page-header row">
		<div class="col-md-6 col-6">
			<h3>Roles</h3>
		</div>
		<div class="col-md-6 col-6 text-right">
			<button class="btn btn-primary" ng-click="addUserRoles();"><i class="icons icon-plus"></i> Add New</button>
		</div>
	</div>
		
	<div class="portlet">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6">
					<ul class="menu">
						<li class="active">
							<a href="#">List of Roles</a>
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
							<th>SN</th>
							<th>Title</th>
							<th>Access Rights</th>
							<th class="text-right" style="width: 150px;">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="roles in users_roles track by $index">
							<td>@{{$index + 1}}</td>
							<td class="theme-color"><b>@{{roles.title}}</b></td>
							<td style="font-size: 12px; color: #888">@{{roles.access_items}}</td>
							<td class="text-right">
								<button class="btn btn-light btn-sm" ng-click="edit_roles(roles)" ng-if="roles.editable">Edit
								</button>
								<button class="btn btn-danger btn-sm" ng-click="delete_roles(roles)" ng-if="roles.editable">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>

	@include('users.add_user_modals')

</div>
@endsection


