<div class="modal" id="city_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
      	<div class="modal-content">
        	<div class="modal-header">
          		<h4 class="modal-title">City</h4>
          		<button type="button" class="close" ng-click="hideModal()"><i class="icons icon-close"></i></button>
        	</div>
        	<div class="modal-body">

				<div class="form-group">
					<span class="label-control">Select State</span>
					<select class="form-control" ng-model="add_city.state_id" ng-change="selectCity(add_city.state_id)">
						<option ng-value="">Select</option>
						<option ng-repeat="state in state_data" ng-value="state.value">
							@{{state.label}}
						</option>
					</select>
				</div>	

				<div class="form-group">
					<span class="label-control">Select City</span>
					<select class="form-control" ng-model="add_city.base_city_id" ng-change="putCityName()">
						<option ng-value="">Select</option>
						<option ng-repeat="city in city_data" ng-value="city.value">
							@{{city.label}}
						</option>
					</select>
				</div>

				<div class="form-group">
					<span class="label-control">City Name</span>
					<input type="text" class="form-control" ng-model="add_city.city_name">
				</div>	

        	</div>
        	<div class="modal-footer">
	          	<button type="button" class="btn btn-primary" ng-click="saveCity()" ng-disabled="processing_req" >Submit <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
        	</div>
      </div>
    </div>
</div>