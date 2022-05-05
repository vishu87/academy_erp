<div class="filters small-form mt-3" ng-show="filter.show">
	<form ng-submit="searchList()">
		<div class="row">
				
				<!-- cities -->
				<div class="col-md-2 form-group">
          <label class="theme-color">City</label>
          <div ng-repeat="city in parameters.city">
          	<input type="checkbox" ng-click="addFilter('cities',city.value)"> @{{city.label}}
          </div>
        </div>

        <!-- centers -->
				<div class="col-md-2 form-group">
	        <label class="theme-color">Center</label>
          <div ng-repeat="center in filter_centers">
          	<input type="checkbox" ng-click="addFilter('centers',center.value)"> @{{center.label}}
          </div>
	      </div>

        <div class="col-md-2">
        	<div class="form-group">
            <label class="theme-color">Lead Status</label>
            <div ng-repeat="st in parameters.status">
            	<input type="checkbox" ng-click="addFilter('status',st.value)"> @{{st.label}}
            </div>
          </div>

          <div class="form-group">
	        	<label class="theme-color">Lead For</label>
	          <div ng-repeat="st in parameters.lead_for">
            	<input type="checkbox" ng-click="addFilter('lead_for',st.value)"> @{{st.label}}
            </div>
	        </div>
          
        </div>

	      <div class="col-md-2">
	      	<div class="form-group mt-2">
            <label class="theme-color">Lead Source</label>
	          <div ng-repeat="source in parameters.lead_sources">
	          	<input type="checkbox" ng-click="addFilter('sources',source.value)"> @{{source.label}}
	        	</div>
	        </div>
	        
        </div>

        <div class="col-md-2">
	        <div class="form-group">
          	<label class="theme-color">Assigned To</label>
            <select class="form-control" ng-model="filter.assign_to">
                <option>Select</option>
                <option value="-1">Not Assigned</option>
                <option ng-repeat="member in members" ng-value="member.id">@{{member.name}}</option>
            </select>
          </div>

          <div class="form-group mt-3">
          	<label class="theme-color">Action Date</label>
            <input type="text" ng-model="filter.action_date_start" class="form-control datepicker" autocomplete="off" placeholder="From">
            <input type="text" ng-model="filter.action_date_end" class="form-control datepicker" autocomplete="off" placeholder="To" style="margin-top: 10px">
          </div>

          <div class="form-group mt-3">
          	<label class="theme-color">Create Date</label>
            <input type="text" ng-model="filter.create_start" class="form-control datepicker" autocomplete="off" placeholder="From">
            <input type="text" ng-model="filter.create_end" class="form-control datepicker" autocomplete="off" placeholder="To" style="margin-top: 10px">
          </div>

          <div class="form-group mt-3">
          	<label class="theme-color">Name</label>
            <input type="text" ng-model="filter.name" class="form-control" autocomplete="off" >
          </div>

          <div class="form-group mt-3">
          	<label class="theme-color">Mobile</label>
            <input type="text" ng-model="filter.mobile" class="form-control" autocomplete="off" >
          </div>

        </div>

        <div class="col-md-2">
        	<div class="mt-5">
        		<button type="submit" class="btn btn-primary btn-block" ng-disabled="processing">Apply Filters <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
	        	<button type="button" ng-click="resetAll()" ng-hide="hide_reset" class="btn btn-block mt-3">Reset Filters</button>

	        	<!-- <button style="margin-top: 23px;" class="btn btn-warning" ng-click="filter.export_excel=1" ladda="loading">Export Excel</button> -->
        	</div>

        </div>

		</div>
	</form>
</div>