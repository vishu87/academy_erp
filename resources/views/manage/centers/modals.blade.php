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


<div class="modal" id="group_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@{{(group.id) ? "Update" : "Add" }} Group</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Group Name <span class="text-danger">*</span></label>
                    <input type="text" ng-model="group.group_name" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select class="form-control" ng-model="group.age_group_category" required convert-to-number>
                        <option value="">Select</option>
                        <option ng-repeat="category in categories" value="@{{category.id}}">@{{category.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Start Date</label>
                    <input type="text" ng-model="group.group_dos" class="form-control datepicker" required>
                </div>                  

                <div class="form-group">
                    <label>Group Capacity</label>
                    <input type="text" ng-model="group.capacity" required class="form-control">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" ng-click="onSubmit_group()" class="btn btn-primary" ng-disabled="groupProcessing">@{{(group.id) ? "Update" : "Create" }} <span ng-show="groupProcessing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="training_day_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@{{(timing.update==true) ? "Update" : "Create" }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">

                <div class="form-group" ng-hide="timing.update">
                    <label>Day</label>
                    <select class="form-control" convert-to-number ng-model="timing.day">
                        <option value="0">Select</option>
                        <option value="@{{day.id}}" ng-repeat="day in days">@{{day.day}}</option>
                    </select>
                </div>

                <div class="form-group ">
                    <label>From Time (24 Hours Format) <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control time"  ng-model="timing.from_time" placeholder="00:00:00">
                </div>
                <div class="form-group">
                    <label>To Time (24 Hours Format) <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control time" placeholder="00:00:00"  ng-model="timing.to_time">
                </div>

                <div class="form-group" ng-show="timing.update">
                    <label>Effective Date</label>
                    <input type="text" class="form-control datepicker"  ng-model="timing.effective_date">
                </div>             

            </div>
            <div class="modal-footer">
                <button type="button" ng-click="addTimingInList()" class="btn btn-primary" ng-disabled="timmingProcessing">@{{(timing.update==true) ? "Update" : "Create" }} <span ng-show="timmingProcessing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="contact_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add/Update Contact</h4>
                <button type="button" class="close" ng-click="hideModal('contact_modal');">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" ng-model="contact_person.member_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" ng-model="contact_person.designation" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" ng-model="contact_person.email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" ng-model="contact_person.mobile" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" ng-click="addPersonToList()" class="btn btn-primary">Add to List</button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="add_center_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Center</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>City <span class="text-danger">*</span></label>
                    <select class="form-control" ng-model="center.city_id">
                        <option value="0">Select</option>
                        <option ng-value="city.id" ng-repeat="city in cities">@{{city.city_name}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Center Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" ng-model="center.center_name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="createNewCenter();" ng-disabled="processing">Add <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>


<div class="modal" id="add_coach_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Coaches</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Coach</label>
                    <select class="form-control" ng-model="coach_id" multiple>
                      <option ng-value="0">Select</option>
                      <option ng-repeat="coach in coachs" ng-value="coach.id">@{{coach.name}}</option>
                    </select>
                </div>

                
            <table class="table table-bordered" style="margin-top: 10px">
                <thead>
                  <tr>
                    <th>Coach Name</th>
                    <th>#</th>
                  </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="gc in group_coach">
                      <td><b>@{{gc.name}}</b></td>
                      <td><button class="btn btn-sm btn-primary" ng-click="removeCoach(gc.id, $index)">Delete</button></td>
                    </tr>

                </tbody>
          </table>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="submitCoach();" ng-disabled="processingCoach">Add <span ng-show="processingCoach" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>