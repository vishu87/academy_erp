@extends('layout')

@section('sub_header')
	<div class="sub-header">
		<div class="table-div full">
			<div>
				<h4 class="fs-18 bold" style="margin:0;">Students</h4>
			</div>
			<div class="text-right">
				<a href="{{url('/students/add-student')}}"><i class="icons icon-plus"></i> Add New student</a>	
			</div>
		</div>
	</div>
@endsection

@section('content')

<div class="" ng-controller="Students_controller" ng-init="init()">

	<label class="switch">
		<input type="checkbox">
		<span class="slider round"></span>
	</label>
		
	<div class="portlet">
		
		<div class="portlet-body ng-cloak">

			<div table-paginate></div>

			<div class="filters" ng-if="filter.show">

				<form name="filterForm" ng-submit="" novalidate>
				
					<div class="row">
						<div class="col-md-2 form-group">
			                <label>City</label>
			                <select  ng-model="filter.city_id" class="form-control" convert-to-number>
			                    <option value="0" >All</option>
			                    <option ng-repeat="city in state_city_center.city" value="@{{city.value}}">@{{city.label}}</option>
			                </select>
			            </div>
			            <div class="col-md-2 form-group">
			                <label>Center</label>
			                <select  ng-model="filter.center_id" class="form-control" convert-to-number>
			                    <option value="0">All</option>
			                    <option ng-repeat="center in state_city_center.center" value="@{{center.value}}" ng-if="filter.city_id == center.city_id ">@{{center.label}}</option>
			                </select>
			            </div>

			            <div class="col-md-2 form-group">
			                <label>Group</label>
			                <select  ng-model="filter.first_group" class="form-control" convert-to-number>
			                	<option value="0">All</option>
			                    <option ng-repeat="age_group in state_city_center.group" value="@{{age_group.value}}" ng-if="filter.center_id == age_group.center_id " ng-hide="filter.center_id == 0">@{{age_group.label}}</option>
			                </select>
			            </div>

			            <div class="col-md-3 form-group">
			                <label style="display: block;">Status</label>
			                <label>
			                	<input type="checkbox" ng-click="addCheckbox('status',0)" ng-checked="filter.status.indexOf(0) > -1">&nbsp; Active
			                </label>
			                &nbsp;&nbsp;&nbsp;
			                <label>
			                	<input type="checkbox"  ng-click="addCheckbox('status',1)" ng-checked="filter.status.indexOf(1) > -1">&nbsp; Inactive
			                </label>
			                &nbsp;&nbsp;&nbsp;
			                <label>
			                	<input type="checkbox"  ng-click="addCheckbox('status',2)" ng-checked="filter.status.indexOf(2) > -1">&nbsp; Paused
			                </label>
			            </div>
			            
			            <div class="col-md-3 form-group ">
			                <label>Pending Renewal</label><br>
		            		<label>
			                	<input type="checkbox" ng-model="filter.pending_renewal" ng-value="1">
			                </label>
			            </div>
			            
			            <div class="col-md-2 form-group">
			                <label>Student Name</label>
		            		<input type="text" ng-model="filter.student_name" class=" form-control">
			            </div>

			            <div class="col-md-2 form-group">
			                <label>Father Name</label>
		            		<input type="text" ng-model="filter.father_name" class=" form-control">
		            		
			            </div>

			            <div class="col-md-2 form-group">
			                <label>Student Mobile</label>
		            		<input type="text" ng-model="filter.mobile" class=" form-control">
		            		
			            </div>

			            <div class="col-md-3 form-group">
			                <label>Subscription Expired Between</label><br>
			                <div class="row">
			                	<div class="col-md-6 form-group">
			                		<input type="text" placeholder="Date from" ng-model="filter.subscription_expeired_from" class="datepicker form-control">
			                	</div>
			                	<div class="col-md-6 form-group">
			                		<input type="text" placeholder="Date to" ng-model="filter.subscription_expeired_to" class="datepicker form-control">
			                	</div>
			                </div>
			            </div>

			            <div class="col-md-12 mb-5">
			            	<button  ng-click="searchList()" class="btn btn-primary" ng-disabled="loading">
			            		Apply
			            		<div class="spinner-border spinner-border-sm text-light" role="status" ng-if="loading">
								  <span class="sr-only">Loading...</span>
								</div>
			            	</button>

			            	<!-- <button  class="btn btn-warning" ng-click="filter.export_excel=1;filterStudents(1)" ladda="loading">Export Excel</button>

							<a  class="btn btn-default" href="admin-api/@{{filter.file_path}}" ng-show="filter.file_path" target="_blank">Download Excel</a> -->
			            </div>
					</div>
				</form>

			</div>

			<div ng-if="loading" class="text-center mt-5 mb-5">
				<div class="spinner-grow" role="status">
				  <span class="sr-only">Loading...</span>
				</div>
			</div>

			<div ng-if="!loading && dataset.length == 0">
				@include('common.no_found',["student"=>true,"message" => "No students found"])
			</div>
			
			<div class="table-responsive" ng-if="!loading && dataset.length > 0">
				<table class="table table-compact">
		 			<thead>
		 				<tr>
		 					<th>SN</th>
		 					<th></th>
		 					<th>Student Name</th>
		 					<th>DOB</th>
		 					<th>City</th>
		 					<th>Center</th>
		 					<th>Group</th>
		 					<th>End Date</th>
		 					<th></th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<tr ng-repeat="student in dataset track by $index">
		 					<td>@{{ (filter.page_no - 1)*filter.max_per_page + $index + 1 }}</td>
		 					<td>
		 						<div class="table-row-pic">
		 							<img ng-src="@{{student.pic}}" style="border: 3px solid @{{student.color}}" />
		 						</div>
		 					</td>
		 					<td>
		 						<div class="item-name">
		 							<a href="" ng-click="studentDetail(student.id)"> 
		 								<b>@{{student.name}} </b>
		 							</a>
		 						</div>
		 					</td>
		 					<td>@{{student.dob}}</td>
		 					<td>@{{student.city_name}}</td>
		 					<td>@{{student.center_name}}</td>
		 					<td>@{{student.group_name}}</td>
		 					<td>@{{student.doe?student.doe:''}}</td>
		 					<td class="text-right">
		 						<a href="{{url('/students/student_details/')}}/@{{student.id}}" class="btn btn-sm btn-light"> 
		 					 		View
		 					 	</a>
							</td>
						
		 				</tr>
		 			</tbody>
			 	</table>
			</div>
		</div>
	</div>
	@include('students.student_personal_detail_modal')
</div>

@endsection

@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/students_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection
