@extends('layout')

@section('content')

<div class="" ng-controller="AccessRights_controller" ng-init="accessRightsInit()">

	<div class="page-header row">
		<div class="col-md-6">
			<h3>List Access Rights</h3>
		</div>
		<div class="col-md-6 text-right">
			<button class="btn btn-primary" 
			ng-click="add_access_rights.show = (add_access_rights.show?false:true)">
			@{{add_access_rights.show?'Hide':'Show'}} Add</button>
		</div>
	</div>

	<div class="portlet" ng-if="add_access_rights.show">
		<div class="portlet-body">
			<div class="filters">
				<form name="filterForm" ng-submit="" novalidate>
					<div class="row">
						<div class="col-md-2 form-group">
							<label>Title :-</label>
							<input type="text" class="form-control" ng-model="add_access_rights.access_rights"/>
						</div>	
					</div>
					<div class="row">
						<div class="col-md-2">
							<button type="Submit" class="btn btn-primary" ng-show="edit"
							ng-click="updateAccessRights()">
								Update
							</button>

							<button type="Submit" class="btn btn-primary" ng-hide="edit"
							ng-click="addAccessRights()">
								Submit
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
		
	<div class="portlet">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6">
					<ul class="menu">
						<li class="active">
							<a href="#">list</a>
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
							<th>sn</th>
							<th>title</th>
							<th>#</th>
						</tr>
					</thead>

					<tbody>
						<tr ng-repeat="rights in users_right_names track by $index">
							<td>@{{$index + 1}}</td>
							<td>@{{rights.access_rights}}</td>
							<td>
								<button class="btn btn-primary btn-sm" ng-click="editRights(rights)" ng-if="access">Edit
								</button>
								<button class="btn btn-danger btn-sm" ng-click="deleteRights(rights)" ng-if="access">Delete
									<i class="icon-trash icons"></i>
								</button>
							</td>
						</tr>
					</tbody>

				</table>

			</div>

		</div>
	</div>

</div>
@endsection


@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/access_rights_controller.js?v='.env('JS_VERSION'))}}" ></script>

@endsection

