@extends('layout')
 
@section('content')

<div ng-controller="CenterController" ng-init="init({{$center_id}})" style="padding: 15px;" class="ng-cloak">
	<div style="margin-bottom: 10px; font-size: 16px;">
		Edit Center
	</div>
	<div ng-show="!loading">
		<form ng-submit="onSubmit()" novalidate>
			
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Center Name *</label>
						<input type="text" class="form-control" ng-model="center.center_name">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Start Date</label>
						<input type="text" class="form-control datepicker" ng-model="center.center_dos">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>App/Website Name *</label>
						<input type="text" class="form-control" ng-model="center.center_app_name">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>City *</label>
						<select class="form-control" ng-model="center.city_id">
							<option ng-value="">Select</option>
							<option ng-repeat="city in cities" ng-value="city.id">@{{city.city_name}}</option>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Invoice City *</label>
						<input type="text" class="form-control" ng-model="center.invoice_city">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Invoice State *</label>
						<input type="text" class="form-control" ng-model="center.invoice_state">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Address</label>
						<input type="text" class="form-control" ng-model="center.address">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Longitude</label>
						<input type="text" class="form-control" ng-model="center.longitude">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Latitude</label>
						<input type="text" class="form-control" ng-model="center.latitude">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Short Url</label>
						<input type="text" class="form-control" ng-model="center.short_url">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Center Capacity</label>
						<input type="text" class="form-control" ng-model="center.center_capacity">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Status</label>
						<select class="form-control" ng-model="center.center_status" convert-to-number>
							<option value="">Select</option>
							<option value="0">Active</option>
							<option value="1">Inactive</option>
						</select>
					</div>
				</div>
				<div class="col-md-3 form-group">
					
					<label>Coordinator</label>
					<select class="form-control" ng-model="center.cordinator_id" convert-to-number>
						<option value="">Select</option>
						<option value="@{{member.id}}" ng-repeat="member in cordinators">@{{member.name}}</option>
					</select>
				</div>


				<div class="col-md-3 form-group">
					
					<label>Mentor</label>
					<select class="form-control" ng-model="center.mentor_id" convert-to-number>
						<option value="">Select</option>
						<option value="@{{member.id}}" ng-repeat="member in members">@{{member.name}}</option>
					</select>
				</div>
				<div class="col-md-3 form-group">
					
					<label>Tech Lead</label>
					<select class="form-control" ng-model="center.tech_lead_id" convert-to-number>
						<option value="">Select</option>
						<option value="@{{member.id}}" ng-repeat="member in members">@{{member.name}}</option>
					</select>
				</div>

				<div class="col-md-3 form-group">
					
					<label>Rent Type</label>
					<select class="form-control" ng-model="center.rent_type" convert-to-number>
						<option value="">Select</option>
						<option value="@{{type.id}}" ng-repeat="type in rent_types">@{{type.type}}</option>
					</select>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Rent Amount</label>
						<input type="text" class="form-control" ng-model="center.rent_amount">
					</div>
				</div>

				<div class="col-md-3 form-group">
					<label>Ground Size(mt)</label>
					<div class="row">
						<div class="col-md-5">
							<input type="text" placeholder="Length" class="form-control" ng-model="center.ground_length">
						</div>
						<div class="col-md-1"><div style="margin-top: 10px">X</div></div>
						<div class="col-md-5">
							<input type="text" placeholder="Width" class="form-control" ng-model="center.ground_width">
						</div>
					</div>
				</div>

				<div class="col-md-3 form-group">
					
					<label>Match Format</label>
					<select class="form-control" ng-model="center.match_format" convert-to-number>
						<option value="">Select</option>
						<option value="@{{format.id}}" ng-repeat="format in match_formats">@{{format.value}}</option>
					</select>
				</div>

				<div class="col-md-3 form-group">
					<label>Hide On App</label><br>
					<label>
						<input type="radio" ng-model="center.hide_on_app" ng-value="1"> &nbsp;Yes
					</label>
					&nbsp;&nbsp;&nbsp;
					<label>
						<input type="radio" ng-model="center.hide_on_app" ng-value="0">&nbsp; No
					</label>
				</div>

				<div class="col-md-6 form-group">
					<label>Meta Title</label>
					<input type="text" ng-model="center.meta_title" class="form-control">
				</div>

				<div class="col-md-6 form-group">
					<label>Meta Description</label>
					<input type="text" ng-model="center.meta_description" class="form-control">
				</div>

				<div class="col-md-6 form-group">
					<label>Meta Keywords</label>
					<input type="text" ng-model="center.meta_keywords" class="form-control">
				</div>

				<div class="col-md-6 form-group">
					<label>Slug</label>
					<input type="text" ng-model="center.slug" class="form-control">
				</div>


				<div class="col-md-12">
					<hr>
					<div style="margin-bottom: 10px; font-size: 16px;">
						Center Images
					</div>
					<div>
						
						<button ng-show="!center_image || center_image == ''" class="button btn default" ngf-select="uploadCenterImage($file,center)" ngf-max-size="5MB" ladda="uploading" data-style="expand-right" >Select Image</button>
					</div>

                    <div class="row" ng-if="center.images.length > 0" style="margin-top: 20px;">
                    	<div class="col-md-2" ng-repeat="image in center.images" style="position: relative;">
                    		<a href="@{{image.image}}" target="_blank">
                    			<img src="@{{image.image_thumb ? image.image_thumb : image.image}}" style="width: 150px; height:auto">
                    		</a>
                    		<a class="btn btn-sm btn-danger" ladda="image.processing" ng-click="removeImage(image,$index)" style="position: absolute;top: 4px;right: 18px">X</a>
                    	</div>
                    </div>
					<hr>
				</div>

				<div class="col-md-12" id="groupAdd">
					<div  style="margin-bottom: 10px; font-size: 16px;">
						<label ng-hide="edit_group_id">Add Groups</label>
						<label ng-show="edit_group_id">Update Groups</label>
					</div>

					<div class="row">
						<div class="col-md-3 form-group">
							<label>Group Name</label>
							<input type="text" ng-model="group.group_name" required class="form-control">
						</div>
						<div class="col-md-3 form-group">
							<label>Start Date</label>
							<input type="text" ng-model="group.group_dos" class="form-control datepicker" required>
						</div>					
						<div class="col-md-3 form-group" ng-show="group.group_type_id != 1">
							<label>Category</label>
							<select class="form-control" ng-model="group.age_group_category" required convert-to-number>
								<option value="">Select</option>
								<option ng-repeat="category in categories" value="@{{category.id}}">@{{category.name}}</option>
							</select>
						</div>

						<div class="col-md-3 form-group">
							<label>Group Capacity</label>
							<input type="text" ng-model="group.capacity" required class="form-control">
						</div>

						<div class="col-md-3 form-group">
							<button class="btn btn-success btn-sm" type="button" 
							ng-click="onSubmit_group(group)">
								<span ng-hide="edit_group_id">Add Groups</span>
								<span ng-show="edit_group_id">Update Groups</span>
							</button>
						</div>
					</div>
				</div>

				<div class="col-md-12"> 
					<hr>
					<div style="margin-bottom: 10px; font-size: 16px;">
						Edit Groups
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<select class="form-control" ng-model="edit_group_id">
								<option value="0">Select</option>
								<option ng-repeat="group in center.groups" 
								ng-value="group.id">
									@{{group.group_name}}
								</option>
							</select>
						</div>
						<div class="form-group col-md-2" >
							<button type="button" class="btn btn-sm btn-success" 
							ng-click="updateGroup(edit_group_id)">
								Edit
							</button>
							<button type="button" class="btn btn-sm btn-danger" 
							ng-click="deleteGroup(edit_group_id)">
								Delete
							</button>
						</div>
					</div>
				</div>

				<div class="col-md-12"><hr></div>

				<div class="col-md-12">
					<div style="margin-bottom: 10px; font-size: 16px;">
						Contact Persons
					</div>
					<div class="row" >
						<div class="form-group col-md-3">
							<label>Name *</label>
							<input type="text" ng-model="contact_person.member_name" class="form-control">
						</div>
						<div class="form-group col-md-3">
							<label>Designation</label>
							<input type="text" ng-model="contact_person.designation" class="form-control">
						</div>
						<div class="form-group col-md-3">
							<label>Email</label>
							<input type="text" ng-model="contact_person.email" class="form-control">
						</div>
						<div class="form-group col-md-3">
							<label>Mobile</label>
							<input type="text" ng-model="contact_person.mobile" class="form-control">
						</div>
						<div class="col-md-3">
							<button type="button" ng-click="addPersonToList()" class="btn btn-sm btn-success">Add to List</button>
						</div>

						<div class="col-md-12" style="margin-top: 10px;">
							
							<table class="table" ng-show="center.contact_persons.length > 0">
								<tr>
									<td>Name</td>
									<td>Designation</td>
									<td>Email</td>
									<td>Mobile</td>
									<td></td>
								</tr>
								<tr ng-repeat="member in center.contact_persons">
									<td>@{{member.member_name}}</td>
									<td>@{{member.designation}}</td>
									<td>@{{member.email}}</td>
									<td>@{{member.mobile}}</td>
									<td><button type="button" ng-click="removePerson($index)" class="btn btn-sm btn-danger pull-right">X</button></td>
									
								</tr>
							</table>
						</div>

						
					</div>
				</div>

				<div class="col-md-12"><hr></div>

				<div class="col-md-12" id="timingRow">
					<div style="margin-bottom: 10px; font-size: 16px;">
						Operation days & timings
					</div>
					<div class="row" >
						<div class="col-md-2 form-group" ng-hide="timing.update">
							<label>Group</label>
							<select class="form-control" ng-model="timing.group_id">
								<option ng-value="">Select</option>
								<option ng-value="group.id" ng-repeat="group in center.groups">@{{group.group_name}}</option>
							</select>
						</div>
						<div class="col-md-2 form-group " ng-hide="timing.update">
							<label>Day</label>
							<select class="form-control" convert-to-number ng-model="timing.day">
								<option ng-value="">Select</option>
								<option value="@{{day.id}}" ng-repeat="day in days">@{{day.day}}</option>
							</select>
						</div>

						<div class="col-md-3 form-group ">
							<label>From Time (24 Hours Format)</label>
							<input type="text" class="form-control time"  ng-model="timing.from_time" placeholder="00:00:00">
						</div>
						<div class="col-md-3 form-group">
							<label>To Time (24 Hours Format)</label>
							<input type="text" class="form-control time" placeholder="00:00:00"  ng-model="timing.to_time">
						</div>

						<div class="col-md-3 form-group" ng-show="timing.update">
							<label>Effective Date</label>
							<input type="text" class="form-control datepicker"  ng-model="timing.effective_date">
						</div>



						<div>
							<button class="btn btn-success btn-sm" style="margin-top: 30px;" type="button" ng-click="addTimingInList()" ladda="timing.processing" >@{{timing.update==true ? 'Update':'Add To List'}}</button>
							<button ng-if="timing.update" type="button" class="btn btn-sm btn-default" style="margin-top: 30px" ng-click="cancelTimingEdit()">Cancel</button>

						</div>
					</div>

					<div><hr></div>

					<div class="row" ng-repeat="group in center.groups track by group.id">
						<div class="col-md-2">
							@{{group.group_name}}<br><br>
							<div class="form-group">
								<label>Batch Capactiy</label>
								<input type="text" ng-model="group.capacity" class="form-control">
							</div>

							<div>
								<ul class="list-group">
									<li class="list-group-item"><b>Active Students : </b>@{{group.active_students}}</li>
									<li class="list-group-item"><b>Pending renewals : </b>@{{group.pending_renewals}}</li>
								</ul>
							</div>

							<button type="button" class="btn btn-default" ng-click="viewCurriculum(group.id)">View Curriculum</button>

						</div>
						<div class="col-md-10">
							
							<table class="table table-bordered" ng-show="group.operation_timings.length >0">
								<thead>
									
									<tr >
										<th>SN</th>
										<th>Day</th>
										<th>From Time</th>
										<th>To Time</th>
										<th class="hidden">Coach</th>
										<th></th>
									</tr>
								</thead>
								
								<tbody>
									<tr ng-repeat="timing in group.operation_timings">
										<td>@{{$index+1}}</td>
										<td>@{{timing.day_name}}</td>
										<td>@{{timing.from_time}}</td>
										<td>@{{timing.to_time}}</td>
										<td class="hidden">@{{timing.coaches_list}}</td>
										<td>
											<button type="button" class="btn btn-sm btn-warning" ng-click="editTiming(timing)">Edit</button>
											<button ng-click="removeTiming(timing)" type="button" class="btn btn-sm btn-danger">X</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12"><hr></div>
					</div>
				</div>

				<div class="col-md-12" style="text-align: center;margin-top: 20px;">
					<button class="btn btn-sm btn-primary" ladda="processing">@{{update==true ? 'Update':'Add'}}</button>
				</div>

			</div>	
		</form>
		
	</div>
	<div ng-show="loading" class="text-center">
		<h3>
		Loading details ...
			
		</h3>
	</div>

	<div class="modal fade in" id="remove-timing" role="dialog">
	    <div class="modal-dialog modal-small">
	        <div class="modal-content">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	            <div class="modal-body">
	            	<form ng-submit="submitRemoveTiming(deleteForm.$valid)" name="deleteForm" novalidate>
	            		
		            	<div class="row">
		            		
			                <div class="col-md-6 form-group">
			                	<label>Effective Date</label>
			                	<input ng-model="open_timing.effective_date" type="text" class="form-control datepicker">
			                </div>
			                <div class="col-md-6">
			                	<button class="btn btn-danger" ladda="open_timing.processing" style="margin-top: 23px">Delete</button>
			                </div>
		            	</div>
	            	</form>
	                
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal fade in" id="viewCurriculum" role="dialog">
	    <div class="modal-dialog modal-small">
	        <div class="modal-content">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	            <div class="modal-body" style="height: 400px; overflow-y: auto">
	            	<table class="table table-bordered">
	            		<tr>
	            			<th style="width: 100px">Date</th>
	            			<th>Level</th>
	            			<th>Concept Type</th>
	            			<th>Concept</th>
	            			<th>Guideline</th>
	            			<th>Objective</th>
	            		</tr>
	            		<tr ng-repeat="event in group_events">
	            			<td>@{{event.start_date | date}}</td>
	            			<td>@{{event.level_name}}</td>
	            			<td>@{{event.concept_type}}</td>
	            			<td>@{{event.concept}}</td>
	            			<td>@{{event.guideline}}</td>
	            			<td>@{{event.objective}}</td>
	            		</tr>
	            	</table>
	                
	            </div>
	        </div>
	    </div>
	</div>
</div>


@endsection


@section('footer_scripts')
  <script type="text/javascript" 
  src="{{url('assets/plugins/admin/scripts/core/center/center_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection