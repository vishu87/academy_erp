<div class="portlet" ng-hide="loading">
  	<div class="portlet-head">
	    <div class="row">
		    <div class="col-md-6">
			    <ul class="menu">
			        <li class="active">
			           	<a href="#">Filter</a>
			        </li>
			    </ul>
		    </div>
		</div>
	</div>
	<div class="portlet-body">
      <div class="filters">
			<form ng-submit="filterStudents()">				
				<div class="row form-group" >
					<div class="col-md-12 filter-box" >
						<div class="table-div full">
							<div><label>City</label></div>
							<div class="text-right">
								<input type="checkbox"  ng-model="check_all_city" ng-click="check_all(1)">
							</div>
						</div>
				        <div ng-repeat="city in cities">
				        	<input type="checkbox" ng-checked="filter['cities'].indexOf(city.value) > -1" ng-click="addFilter('cities',city.value)"> @{{city.label}} <span ng-if="city.city_status == 1">(I)</span>
				        </div>
				    </div>

					<div class="col-md-12 filter-box">
						<div class="table-div full">
				        	<div><label>Center</label></div>
				        	<div class="text-right">
				        		<input type="checkbox"  ng-model="check_all_center" ng-click="check_all(2)"> 
				        	</div>
				        </div>

				        <div ng-repeat="center in filter_centers">
				        	<input type="checkbox" ng-checked="filter['centers'].indexOf(center.value) > -1" ng-click="addFilter('centers',center.value)"> @{{center.label}} <span ng-if="center.center_status == 1">(I)</span>
				        </div>
				    </div>

				    <div class="col-md-12 filter-box">
				    	<div class="table-div full">
				        	<div>
				        		<label>Groups</label>
				        	</div>
				        	<div class="text-right">
				        		<input type="checkbox" ng-model="check_all_groups" ng-click="check_all(3)">
				        	</div>
				        </div>

				        <div ng-repeat="group in filter_groups">
				        	<input  type="checkbox" ng-checked="filter['groups'].indexOf(group.value) > -1" ng-click="addFilter('groups',group.value)"> @{{group.label}} (@{{group.center_name}}) <span ng-if="group.group_status == 1">(I)</span>
				        </div>
				    </div>


			    	<div class="col-md-12 filter-box">
			            <label>Status</label>
			            <div>
			            	<input type="checkbox" ng-checked="filter['status'].indexOf(0) > -1" ng-click="addFilter('status',0)"> Active &nbsp;&nbsp;
			            	<input type="checkbox" ng-checked="filter['status'].indexOf(1) > -1" ng-click="addFilter('status',1)"> Inactive
			            </div>
			    	</div>

			    	<div class="col-md-12 filter-box">
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

		            <div class="col-md-12 filter-box">
		            	<label>Renewal Dates</label><br>
			    		<div class="row">
			            	<div class="col-md-6 form-group">
			            		<input type="text" placeholder="Min" ng-model="filter.min_renew_days" class=" form-control datepicker">
			            	</div>
			            	<div class="col-md-6 form-group">
			            		<input type="text" placeholder="Max" ng-model="filter.max_renew_days" class=" form-control datepicker">
			            	</div>
			            </div>
		            </div>

			        <div class="form-group col-md-12 filter-box">
			            <label>Student Mobile <span style="font-style: italic; font-size: 11px;">(put comma for multiple)</span></label><br>
			            <input type="text"  ng-model="filter.mobile" class=" form-control">
			        </div>

				</div>
			</form>
		</div>
	</div>
</div>