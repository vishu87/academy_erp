@extends('layout')

@section('content')

<div class="ng-cloak" ng-controller="DropDownMasterController" ng-init="init();">
	
	<div class="page-header row">
		<div class="col-6">
			<h3>Drop Down Master</h3>
		</div>
		<div class="col-6">
			<div class="text-right">
				<!-- <button type="button" class="btn btn-primary" ng-click="addGroupType()"><i class="icons icon-plus"></i> Add</button> -->
			</div>
		</div>
	</div>

	<div ng-if="loading" class="text-center mt-5 mb-5">
		<div class="spinner-grow" role="status">
		  <span class="sr-only">Loading...</span>
		</div>
	</div>


	<div class=" row" ng-if="!loading">
		<div class="col-md-4" >
			<div class="portlet">
				<div class="portlet-head">
					<div class="row">
					<div class="col-md-6">
						<ul class="menu">
						  <li class="active">
						    <a href="#">Group Tppes</a>
						  </li>
						</ul>
					</div>

					<div class="col-md-6 text-right">
						<button style="margin-top: 10px;" type="button" class="btn  btn-primary" ng-click="addGroupType()"><i class="icons icon-plus"></i> Add</button>
					  	<!-- <button type="button" class="btn btn-sm btn-light" ng-click="editCategory(parameter)">Edit</button>&nbsp;
	            		<button type="button" class="btn btn-sm btn-danger" ng-click="deleteCategory(parameter.id)">Delete</button> -->
					</div>
					</div>
				</div>
				<div class="portlet-body">
	            	<div class="text-right">
						<!-- <button type="button" class="btn btn-primary" ng-click="addGroupType()"><i class="icons icon-plus"></i> Add</button> -->
	            	</div>
	            	<div class="mt-2">
	            		<table class="table table-compact">
			            	<tr ng-repeat="group_type in group_types track by $index">
		        				<td>@{{ group_type.name }}</td>
		        				<td class="text-right" style="min-width: 130px">
		        					<button type="button" class="btn btn-sm btn-light" ng-click="edit(group_type)">Edit</button>&nbsp;&nbsp;
									<button type="button" class="btn btn-sm btn-danger" ng-click="delete(group_type.id, $index)">Delete</button>
		        				</td>
		        			</tr>
	            		</table>
		        	</div> 
				</div>
			</div>
		</div>
	</div>

	@include('dropDownMaster.groupTypes.group_type_modal')
</div>

@endsection