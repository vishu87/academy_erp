@extends('layout')

@section('content')

<div class="ng-cloak" ng-controller="Parameters_controller" ng-init="sport_id = 1; init()">
	
	<div class="page-header row">
		<div class="col-6">
			<div class="row">
				<div class="col-md-4">
					<h3>Parameters</h3>
				</div>
			</div>
		</div>
		<div class="col-6 text-right">
			<select ng-model="group_type_id" class="form-control" ng-change="changeGroup()" convert-to-number style="display: inline-block; width: 160px">
				<option value="0">Master Data</option>
				<optgroup label="Group Types">
					<option ng-repeat="group_type in group_types" ng-value="@{{group_type.id}}">for @{{group_type.name}}</option>
				</optgroup>
			</select>
			<button type="button" class="btn btn-primary" ng-click="addCategory()" ng-show="group_type_id == 0"><i class="icons icon-plus"></i> Add Category</button>
		</div>
	</div>

	<div ng-if="loading" class="text-center mt-5 mb-5">
		<div class="spinner-grow" role="status">
		  <span class="sr-only">Loading...</span>
		</div>
	</div>

	<div class=" row" ng-if="!loading">
		<div class="col-md-4" ng-repeat="parameter in parameters track by $index">
			<div class="portlet">
				<div class="portlet-head">
					<div class="row">
					<div class="col-md-6 col-6">
						<ul class="menu">
						  <li class="active">
						    <a href="#">@{{ parameter.category_name }}</a>
						  </li>
						</ul>
					</div>
					<div class="col-md-6 col-6 text-right" ng-show="group_type_id == 0">
					  	<button type="button" class="btn btn-sm btn-light" ng-click="editCategory(parameter)">Edit</button>&nbsp;
	            		<button type="button" class="btn btn-sm btn-danger" ng-click="deleteCategory(parameter.id)">Delete</button>
					</div>
					</div>
				</div>
				<div class="portlet-body">
	            	<div class="text-right" ng-show="group_type_id == 0">
	            		<button type="button" class="btn btn-primary btn-sm" ng-click="addAttribute(parameter.id)">Add Attribute</button>
	            	</div>
	            	<div class="mt-2">
	            		<table class="table table-compact">
	            			<tr ng-repeat="att in parameter.attributes">
	            				<td>@{{ att.attribute_name }}</td>
	            				<td class="text-right" style="min-width: 130px" ng-show="group_type_id == 0">
	            					<button type="button" class="btn btn-sm btn-light" ng-click="editAttribute(att)">Edit</button>&nbsp;&nbsp;
	    							<button type="button" class="btn btn-sm btn-danger" ng-click="deleteAttribute(att.id)">Delete</button>
	            				</td>
	            				<td class="text-right" style="min-width: 130px" ng-show="group_type_id != 0">
	            					<label class="switch">
										<input type="checkbox" ng-checked="att.value == 1" ng-click="toggleAttr(att)">
										<span class="slider round"></span>
									</label>
	            				</td>
	            			</tr>
	            		</table>
		        	</div> 
				</div>
			</div>
		</div>
	</div>
	@include('parameters.category_modal')
	@include('parameters.attribute_modal')
</div>

@endsection