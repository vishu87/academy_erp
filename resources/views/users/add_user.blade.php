@extends('layout')

@section('content')


<div class="ng-cloak" ng-controller="User_controller" ng-init="editUserInit({{ $update }})">

	<div class="page-header row">
		<div class="col-md-6">
			<h3>User Profile: @{{ add_user.name }}</h3>
		</div>
		<div class="col-md-6 text-right">
			<a href="{{url('/users/view')}}" class="btn btn-primary">Reset Password</a>
			<a href="{{url('/users/view')}}" class="btn btn-secondary">Go Back</a>
		</div>
	</div>

	<div class="row">

		<div class="col-md-6">

			<div class="portlet">
				<div class="portlet-head">
					<div class="row">

						<div class="col-md-6">
							<ul class="menu">
								<li class="active">
									<a href="#">User Detail</a>
								</li>
							</ul>
						</div>

					</div>
				</div>	

				<div class="portlet-body ng-cloak">
					<form method="POST"  name="UserForm" ng-submit="saveUser(UserForm.$valid)" novalidate="novalidate">
						<div class="row">
							<div class="col-md-6 form-group">
								<label>Username <span class="required">*</span></label>
								<input type="text" class="form-control" ng-model="add_user.username" ng-readonly="user_id != 0"/>
							</div>

							<div class="col-md-6 form-group">
								<label>Name <span class="required">*</span></label>
								<input type="text" class="form-control" ng-model="add_user.name"/>
							</div>

							<div class="col-md-6 form-group">
								<label>Email</label>
								<input type="email" class="form-control" ng-model="add_user.email" />
							</div>

							<div class="col-md-6 form-group">
								<label>Role <span class="required">*</span></label>
								<select class="form-control" ng-model="add_user.role" ng-change="getUserAccess()" ng-required="ac_provideer">
									<option value="0">Select</option>
									<option ng-value="@{{names.id}}"  
									ng-repeat="names in users_roles">@{{names.title}}</option>
								</select>
							</div>

							<div class="col-md-6 form-group">
								<label>Mobile</label>
								<input type="text" class="form-control" ng-model="add_user.mobile" ng-pattern="/^[0-9]{10}$/" ng-pattern-err-type="PatternMobile">
							</div>

							<div class="col-md-6 form-group">
			                    <label>City <span class="required">*</span></label>
			                    <select class="form-control" ng-model="add_user.city_id">
			                      <option ng-value="0">Select</option>
			                      <option ng-value="@{{city.value}}"  
			                      ng-repeat="city in all_city_list">@{{city.label}}</option>
			                    </select>
		                  	</div>

							<div class="col-md-6 form-group">
								<label>Address</label>
								<input type="text" class="form-control" ng-model="add_user.address"  >
							</div>

							<div class="col-md-6 form-group">
								<label>Employee Code</label>
								<input type="text" class="form-control" ng-model="add_user.emp_code"  >
							</div>

							<div class="col-md-6 form-group">
								<label>Date of Joining</label>
								<input type="text" class="form-control datepicker" ng-model="add_user.doj"  >
							</div>

							<div class="col-md-6 form-group">
								<label>Gender</label>
								<select class="form-control" ng-model="add_user.gender">
									<option ng-value='0'>Select</option>	
									<option ng-value="1">Male</option>	
									<option ng-value="2">Female</option>	
								</select>
							</div>

							<div class="col-md-6 form-group">
								<label>Licenses</label>
								<input type="text" class="form-control" ng-model="add_user.license" >
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-md-4">
								<table class="table table-bordered">
									<tr ng-repeat="sport in sports">
										<td>
										    <input type="checkbox" value="@{{sport.id}}" ng-click="addSports(sport.id,'sports_ids')" ng-checked=" add_user.sports_ids.indexOf(sport.id) > -1 ">
										    <label>@{{sport.sport_name}}</label>
										</td>
									</tr>
								</table>
							</div>
						</div> -->

						<button type="Submit" class="btn btn-primary" ng-disabled="processing_req">Submit <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>

					</form>	
				</div>
			</div>

		</div>
		<div class="col-md-6">

			<div class="portlet">
				<div class="portlet-head">
					<div class="row">

						<div class="col-md-6">
							<ul class="menu">
								<li class="active">
									<a href="#">Access</a>
								</li>
							</ul>
						</div>

					</div>
				</div>	

				<div class="portlet-body ng-cloak">
					@include("users.add_access_rights")
				</div>
			</div>

		</div>
	</div>

	@include('users.add_user_modals')
</div>

@endsection