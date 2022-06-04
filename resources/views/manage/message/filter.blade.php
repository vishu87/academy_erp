<div class="portlet" ng-hide="loading">
  	<div class="portlet-head">
	    <div class="row">
		    <div class="col-md-6">
			    <ul class="menu">
			        <li class="active">
			           	<a href="#">Filters</a>
			        </li>
			    </ul>
		    </div>
		</div>
	</div>
	<div class="portlet-body">
      <div class="filters">
			<form ng-submit="filterStudents()">				
				<div class="row form-group" >
					<div class="col-md-12 ">
				        <label>City</label>
				        <input type="checkbox"  ng-model="check_all_city" ng-click="check_all(1)"> Select All
				        <div ng-repeat="city in cities">
				        	<input type="checkbox" ng-checked="filter['cities'].indexOf(city.id) > -1" ng-click="addFilter('cities',city.id)"> @{{city.city_name}} <span ng-if="city.city_status == 1">(I)</span>
				        </div>
				    </div>

					<div class="col-md-12 ">
				        <label>Center</label>
				        <input type="checkbox"  ng-model="check_all_center" ng-click="check_all(2)"> Select All

				        <div ng-repeat="center in filter_centers">
				        	<input type="checkbox" ng-checked="filter['centers'].indexOf(center.id) > -1" ng-click="addFilter('centers',center.id)"> @{{center.center_name}} <span ng-if="center.center_status == 1">(I)</span>
				        </div>
				    </div>

				    <div class="col-md-12 ">
				        <label>Groups</label>
				        <input type="checkbox" ng-model="check_all_groups" ng-click="check_all(3)"> Select All

				        <div ng-repeat="group in filter_groups">
				        	<input  type="checkbox" ng-checked="filter['groups'].indexOf(group.id) > -1" ng-click="addFilter('groups',group.id)"> @{{group.group_name}} (@{{group.center_name}}) <span ng-if="group.group_status == 1">(I)</span>
				        </div>
				    </div>

				    <div class="col-md-12 ">

				    	<div>
				            <label>Status</label>
				            <div>
				            	<input type="checkbox" ng-checked="filter['status'].indexOf(0) > -1" ng-click="addFilter('status',0)"> Active &nbsp;&nbsp;
				            	<input type="checkbox" ng-checked="filter['status'].indexOf(1) > -1" ng-click="addFilter('status',1)"> Inactive
				            </div>
				    	</div>
				    	<div style="margin-top: 10px">
				            <label style="display: block;">Batch Types</label>
				            <div ng-repeat="batch_type in batch_types" style="display: inline-block;">
				            	<input type="checkbox" ng-checked="filter['batch_types'].indexOf(batch_type.id) > -1" ng-click="addFilter('batch_types',batch_type.id)"> @{{batch_type.name}} &nbsp;&nbsp;&nbsp;
				            </div>
				        </div>

				    	<div style="margin-top:20px">
				    		<div class="row">
				    			<div class="col-md-6">
				    				<label>DOB Start Date</label>
					                <div style="margin-bottom:10px">
					                	<input type="text" ng-model="filter.date_start" class="form-control datepicker" ng-change="getStudents(1)" />
					                </div>		
				    			</div>
				    			<div class="col-md-6">
				    				<label>DOB End Date</label>
					                <div>
					                	<input type="text" ng-model="filter.date_end" class="form-control datepicker" ng-change="getStudents(1)" />
					                </div>	
				    			</div>
				    		</div>
				    	</div>

			            <label>Renewal Dates</label><br>
			    		<div class="row">
			            	<div class="col-md-6 form-group">
			            		<input type="text" placeholder="Min" ng-model="filter.min_renew_days" class=" form-control datepicker">
			            	</div>
			            	<div class="col-md-6 form-group">
			            		<input type="text" placeholder="Max" ng-model="filter.max_renew_days" class=" form-control datepicker">
			            	</div>
			            </div>

			            <label>Paused?</label><br>
			            <label>
			            	<input type="radio" ng-model="filter.paused" value="1"> Yes &nbsp;
			            </label>
			            <label>
			            	<input type="radio" ng-model="filter.paused" value="2"> No &nbsp;
			            </label>
			            <label>
			            	<input type="radio" ng-model="filter.paused" value="0"> All &nbsp;
			            </label>



			            <label>Downloaded App?</label><br>
			            <label>
			            	<input type="radio" ng-model="filter.downloaded_app" value="1"> Yes &nbsp;
			            </label>
			            <label>
			            	<input type="radio" ng-model="filter.downloaded_app" value="2"> No &nbsp;
			            </label>
			            <label>
			            	<input type="radio" ng-model="filter.downloaded_app" value="0"> All &nbsp;
			            </label>

				        <div class="form-group">
				            <label>Student Mobile <span style="font-style: italic;">put comma for multiple</span></label><br>
				            <input type="text"  ng-model="filter.mobile" class=" form-control">
				        </div>
				    </div>
				</div>
			</form>
		</div>
	</div>
</div>