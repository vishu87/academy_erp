@extends('layout')

@section('sub_header')
	<div class="sub-header">
		<div class="table-div full">
			<div>
				<h4 class="fs-18 bold" style="margin:0;"> {{ $id ? "Update Student" : "Add New Student" }} </h4>
			</div>
			<div class="text-right">
				@if(!$id)<a href="{{url('/students')}}"><i class="icons icon-arrow-left"></i> Go Back</a>@else
				<a href="{{url('/students/student_details/'.$id)}}"><i class="fa fa-angle-left"></i> Go Back</a>@endif
			</div>
		</div>
	</div>
@endsection

@section('content')

<div class="ng-cloak" ng-controller="Students_profile_controller" ng-init="editStudent('{{$id}}')">

	<div class="portlet">

		<div ng-if="loading" class="text-center mt-5 mb-5">
			<div class="spinner-grow" role="status">
			  <span class="sr-only">Loading...</span>
			</div>
		</div>

		<div class="portlet-body" ng-if="!loading">
			
			<div class=" mt-1">

				<form method="POST" name="StudentForm" ng-submit="add_data(StudentForm.$valid)" novalidate="novalidate" >
					<div ng-if="{{!$id}}">
						<h4 class="section-title">Batch Details</h4>
						<div class="row">
							
							<div class="col-sm-3 form-group">
								<label>City <span class="required">*</span></label>
								<select ng-model="add_student.city_id" class="form-control" required>
									<option value="0">Select</option>
									<option ng-repeat="city in state_city_center.city" 
									ng-value="@{{city.value}}">@{{city.label}}
									</option>
								</select>
							</div>

							<div class="col-sm-3 form-group">
								<label>Center <span class="required">*</span></label>
								<select ng-model="add_student.center_id" class="form-control" required>
									<option value="0">Select</option>
									<option ng-repeat="center in state_city_center.center" 
									ng-value="@{{center.value}}"  ng-if="center.city_id == add_student.city_id">@{{center.label}}
									</option>
								</select>
							</div>

							<div class="col-sm-3 form-group">
								<label>Group <span class="required">*</span></label>
								<select ng-model="add_student.group_id" class="form-control" required>
									<option value="0">Select</option>
									<option ng-repeat="group in state_city_center.group" 
									ng-value="@{{group.value}}"  ng-if="group.center_id == add_student.center_id">@{{group.label}}
									</option>
								</select>
							</div>
						</div>
					</div>

					<h4 class="section-title">Personal Details</h4>
					<div class="row">
						<div class="col-sm-3 form-group">
							<label>Name <span class="required">*</span></label>
							<input type="text" class="form-control" ng-model="add_student.name" required="" />
						</div>

						<div class="col-sm-3 form-group">
							<label>Gender <span class="required">*</span></label><br>
							<label>
								<input type="radio" ng-value="1" ng-model="add_student.gender" required /> &nbsp;Male
							</label>&nbsp;&nbsp;&nbsp;
							<label>
								<input type="radio" ng-value="2" ng-model="add_student.gender" required /> &nbsp;Female
							</label>
						</div>

						<div class="col-sm-3 form-group">
							<label>DOB <span class="required">*</span></label>
							<input type="text" class="form-control datepicker" ng-model="add_student.dob" required />
						</div>

						<div class="col-sm-3 form-group">
							<label>Mobile</label>
							<input type="text" class="form-control" ng-model="add_student.mobile" ng-pattern="/^[0-9]{10}$/" ng-pattern-err-type="PatternMobile" />
						</div>

						<div class="col-sm-3 form-group">
							<label>Email</label>
							<input type="email" class="form-control" ng-model="add_student.email" />
						</div>

						<div class="col-sm-3 form-group">
							<label>School</label>
							<input type="text" class="form-control" ng-model="add_student.school" />
						</div>
					</div>

					<h4 class="section-title">Guardian Details</h4>

					<div class="row" ng-repeat="guardian in add_student.guardians track by $index">
						<div class="col-sm-3 form-group">
							<label>Name <span class="required">*</span></label>
							<input type="text" class="form-control" ng-model="guardian.name" required />
						</div>

						<div class="col-sm-3 form-group">
							<label>Relation <span class="required">*</span></label>
							<select class="form-control" ng-model="guardian.relation_type" required />
								<option ng-value="">Select</option>
								<option ng-value="1">Father</option>
								<option ng-value="2">Mother</option>
								<option ng-value="3">Other</option>
							</select>
						</div>

						<div class="col-sm-2 form-group">
							<label>Mobile</label>
							<input type="text" class="form-control" ng-model="guardian.mobile" ng-pattern="/^[0-9]{10}$/" ng-pattern-err-type="PatternMobile" />
						</div>

						<div class="col-sm-3 form-group">
							<label>Email</label>
							<input type="email" class="form-control" ng-model="guardian.email" />
						</div>

						<div class="col-sm-1 form-group">
							<label style="display: block;">&nbsp;</label>
							<button type="button" class="btn btn-sm btn-danger" ng-click="removeGuardian($index)"><i class="fa fa-remove"></i>Remove</button>
						</div>

					</div>
					<button type="button" class="btn-light btn" ng-click="addGuardian()">+ Add
					</button>

					<h4 class="section-title">Address</h4>
					<div class="row">

						<div class="col-sm-3 form-group">
							<label>Address <span class="required">*</span></label>
							<input class="form-control" ng-model="add_student.address" required />
						</div>

						<div class="col-sm-3 form-group">
							<label>State <span class="required">*</span></label>
							<select ng-model="add_student.state_id" name="state_id" class="form-control" ng-change="selectCity(add_student.state_id)" required >
								<option value="0">Select</option>
								<option ng-repeat="state in state_city_center.state" 
								ng-value="@{{state.value}}">@{{state.label}}
								</option>
							</select>
						</div>

						<div class="col-sm-3 form-group">
							<label>City <span class="required">*</span></label>
							<select ng-model="add_student.state_city_id" name="state_city_id" class="form-control" required >
								<option value="0">Select</option>
								<option ng-repeat="city in cities" 
								ng-value="@{{city.value}}">@{{city.label}}
								</option>
							</select>
						</div>

					</div>

					<!-- <h4 ng-show="@{{add_student.parameter > 0}}">Other Paramters</h4>
					<div class="row" ng-show='@{{add_student.parameter > 0}}'>

						<div class="col-sm-3 form-group" 
						ng-repeat="par in add_student.parameter">
							<label>@{{par.parameter}}</label>

							<input type="text" class="form-control" ng-model="par.parameter_data"
							ng-if="par.parameter_type == 'text' " />

							<select class="form-control" ng-model="par.parameter_data"
							ng-if="par.parameter_type == 'select' ">

								<option ng-if="par.parameter_type == 'select' " value="select">
									Select
								</option>
								<option ng-if="par.parameter_type == 'select' " value="@{{value}}"
								ng-repeat="value in par.parameter_values track by $index">
									@{{value}}
								</option>

							</select>

						</div>
					</div> -->

					<div class="">
						<hr>
						<button type="submit" class="btn btn-primary" ng-disabled="processing">	  
							@{{add_student.edit ? 'Update':'Submit'}} <span ng-if="processing" class="spinner-border spinner-border-sm"></span>
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	@include('students.addStudentModal')
</div>
@endsection


@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/students_profile_controller.js?v='.env('JS_VERSION'))}}" ></script>

@endsection