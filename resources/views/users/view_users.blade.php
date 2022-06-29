@extends('layout')

@section('content')

<div class="" ng-controller="User_controller" ng-init="userInit()">

	<div class="page-header row">
		<div class="col-md-6">
			<h3>Users</h3>
		</div>
		<div class="col-md-6 text-right">
			<button type="button" class="btn btn-primary" ng-click="addUserModal();"><i class="icons icon-plus"></i> Add User</button>
		</div>
	</div>

	<div class="portlet">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6">
					<ul class="menu">
						<li class="active">
							<a href="#">All Users</a>
						</li>
					</ul>
				</div>

			</div>
		</div>	

		<div class="portlet-body ng-cloak">

			<div class="table-responsive">
				<table class="table">
					<thead class="">
						<tr>
							<th>SN</th>
							<th>Name</th>
							<th>Username</th>
							<th>Mobile</th>
							<th>City</th>
							<th>Role</th>
							<th class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="user in users_list track by $index" ng-class="user.inactive == 1 ? 'opaque' : '' ">
							<td>@{{$index + 1}}</td>
							<td class="theme-color"><b>@{{user.name}}</b></td>
							<td>@{{user.username}}</td>
							<td>@{{user.mobile}}</td>
							<td>@{{user.city_name}}</td>
							<td>@{{user.title}}</td>
							<th class="text-right">
								<a type="button" class="btn btn-light btn-sm"
								href="{{url('/users/edit/')}}/@{{user.id}}" ng-if="user.inactive == 0">Edit</a>
								<button class="btn btn-sm btn-danger" ng-click="deleteUser(user)" ng-if="user.inactive == 0">Mark Inactive</button>
								<button class="btn btn-sm btn-light" ng-click="deleteUser(user)" ng-if="user.inactive == 1">Mark Active</button>
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