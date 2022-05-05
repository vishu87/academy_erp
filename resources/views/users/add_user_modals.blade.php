  <div class="modal" id="location_access" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Access Rights Location</h4>
          <button type="button" class="close" ng-click="hide_location_access_model()">
          &times;</button>
        </div>
        <div class="modal-body">
          <div class="row">

          	<div class="col-md-4 form-group" ng-show="access_location_data.city" 
          	ng-if="access_location_data.city">
          		<label class="label-control">City</label>
          		<select class="form-control" ng-model="access_location_data.city_id" 
              ng-change="clear_center_ids()">
          			<option value="-1">Select</option>
          			<option ng-repeat="city in all_city_list track by $index" value="@{{city.value}}">
          				@{{city.label}}
          			</option>
          		</select>

          	</div>


          	<div class="col-md-8 form-group" ng-if="access_location_data.center"
          	 ng-hide="access_location_data.group">

          		<label class="label-control">Center</label>
          		<div class="row" ng-repeat="center in all_center_list track by $index">

          			<input type="checkbox" ng-if="access_location_data.city_id == center.city_id" ng-value="center.value" ng-click="add_center_in_access_location(center.value)" ng-checked="access_location_data.center_ids.indexOf(names.id) > -1" > 
          			<label class="label-control col-md-11" ng-if="access_location_data.city_id == center.city_id">
          				@{{center.label}}
          			</label>
          		
          		</div>

          	</div>


          	<div class="col-md-8 form-group" ng-show="access_location_data.group">
          		<label class="label-control">Center</label>
          		
          		<select class="form-control" ng-model="access_location_data.center_id"
              ng-change="clear_group_ids()">
          			<option value="0">Select</option>
          			<option ng-repeat="center in all_center_list track by $index" 
          			value="@{{center.value}}" ng-if="access_location_data.city_id == center.city_id">
          				@{{center.label}}
          			</option>
          		</select>

          	</div>



          	<div class="col-md-8 form-group" ng-show="access_location_data.group" 
          	ng-if="access_location_data.group">

          		<label class="label-control">Group</label>

          		<div ng-repeat="group in all_group_list track by $index">
          			
                <input type="checkbox" ng-if="access_location_data.center_id == group.center_id"
          			ng-click="add_group_in_access_location(group.value)" />
          			<label class="label-control col-md-11" ng-if="access_location_data.center_id == group.center_id">
          				@{{group.label}}
          			</label>

          		</div>
          	</div>
          	
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" 
          ng-click="submit_access_location_data()">
      		Submit
      	  </button>

          <button type="button" class="btn btn-default" 
          ng-click="hide_location_access_model()">Close</button>
        </div>
      </div>
      
    </div>
	</div>

<div class="modal" id="add_user_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create User</h4>
                <button type="button" class="close" ng-click="hideModal('add_user_modal');"><i class="icons icon-close"></i></button>
            </div>
            <form method="POST"  name="UserForm" ng-submit="saveUser(UserForm.$valid)" novalidate="novalidate">
              <div class="modal-body">
                  <div class="form-group">
                    <label>Name <span class="required">*</span></label>
                    <input type="text" class="form-control" ng-model="add_user.name" required />
                  </div>
                  <div class="form-group">
                    <label>Username <span class="required">*</span></label>
                    <input type="text" class="form-control" ng-model="add_user.username" ng-pattern="/^[A-Za-z0-9.]{4,20}$/" ng-pattern-err-type="PatternUsername" required />
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Role <span class="required">*</span></label>
                        <select class="form-control" ng-model="add_user.role" required >
                          <option value="">Select</option>
                          <option ng-value="@{{names.id}}"  
                          ng-repeat="names in users_roles">@{{names.title}}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>City (for Attendance)<span class="required">*</span></label>
                        <select class="form-control" ng-model="add_user.city_id" required>
                          <option value="">Select</option>
                          <option ng-value="@{{city.value}}" ng-repeat="city in cityCenter.city">@{{city.label}}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" ng-model="add_user.email" />
                  </div>

                  <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" class="form-control" ng-model="add_user.mobile" ng-pattern="/^[0-9]{10}$/" ng-pattern-err-type="PatternMobile" />
                  </div>
                  
              </div>
              <div class="modal-footer">
                  <button type="Submit" class="btn btn-primary" ng-disabled="processing_req">Submit <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
              </div>
            </form>
      </div>
    </div>
</div>

<div class="modal" id="add_roles_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Role</h4>
                <button type="button" class="close" ng-click="hideModal('add_roles_modal');">&times;</button>
            </div>
            <form name="filterForm" ng-submit="" novalidate>         
              <div class="modal-body">
                <div class="form-group">
                  <label class="label-control">Title</label>
                  <input type="text" class="form-control" ng-model="add_roles.title"/>
                </div>  

                <div class="form-group">
                  <label class="label-control">Access Rights</label>
                  <div ng-repeat="names in users_right_names">
                    <input type="checkbox" ng-click="add_rights(names.id)"
                    ng-checked="add_roles.access_rights.indexOf(names.id) > -1"  
                    ng-value="names.id">
                    <label class="label-control">@{{names.access_rights}}</label><br>
                  </div>  
                </div>
              </div>
              <div class="modal-footer">
                  <button type="Submit" class="btn btn-primary" ng-click="add_user_roles()" ng-disabled="processing_req">Submit <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
              </div>
            </form>
      </div>
    </div>
</div>