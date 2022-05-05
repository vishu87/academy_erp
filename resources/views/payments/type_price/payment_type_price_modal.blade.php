<div class="modal" id="price_type_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="modal-title">Set Amount</h4>
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <div class="row">
	          	<div class="col-md-3 form-group">
	          		<label>Price</label>
	          		<input type="text" class="form-control" ng-keyup="countTotal(add_PPD)" ng-model="add_PPD.price">
	          	</div>
	          	<div class="col-md-3 form-group">
	          		<label>Tax (%)</label>
	          		<input type="text" readonly class="form-control" ng-keyup="countTotal(add_PPD)" ng-model="add_PPD.tax">
	          	</div>
	          	<div class="col-md-3 form-group">
	          		<label>Total Amount</label>
	          		<input type="text" readonly class="form-control" ng-model="add_PPD.total_amt">
	          	</div>
	      </div>
	    </div>
	    <div class="modal-footer">
        
        <button type="button" class="btn btn-primary" ng-show="edit" ng-click="updatePayPriceData(add_PPD)" ng-disabled="processing_req">
        	Update
        	<div class="spinner-border spinner-border-sm text-light" role="status" ng-if="processing_req">
            <span class="sr-only">Loading...</span>
          </div>
        </button>
	      
	      <button type="button" class="btn btn-primary" ng-hide="edit" ng-click="addPayPriceData(add_PPD)"  ng-disabled="processing_req">
	      	Submit
	      	<div class="spinner-border spinner-border-sm text-light" role="status" ng-if="processing_req">
            <span class="sr-only">Loading...</span>
          </div>
	      </button>

	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>

	  </div>
	</div>
</div>

 <div class="modal" id="price_access" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Payment Location</h4>
          <button type="button" class="close" ng-click="hide_data_modal('price_access')">
          &times;</button>
        </div>
        <div class="modal-body">
          <div class="row">

            <div class="col-md-8 form-group" ng-if="add_PPD.city" 
              ng-hide="add_PPD.center">
              <label class="label-control">Select Cities</label>
                <div ng-repeat="city in price_type.city track by $index">
                  <input type="checkbox" ng-value="city.value" ng-click="add_city_in_access_location(city.value)"
                  ng-checked="price_type.city_ids.indexOf(city.value) > -1">
                  <label class="label-control">
                    @{{city.label}}
                  </label>
                </div>
            </div>

          	<div class="col-md-4 form-group" ng-show="add_PPD.city" 
          	ng-if="add_PPD.center">
          		<label class="label-control">Select City</label>
          		<select class="form-control" ng-model="add_PPD.city_id" ng-change="clear_center_ids()">
          			<option value="-1">Select</option>
          			<option ng-repeat="city in price_type.city track by $index" value="@{{city.value}}">
          				@{{city.label}}
          			</option>
          		</select>
          	</div>

          	<div class="col-md-8 form-group" ng-if="add_PPD.center"
          	 ng-hide="add_PPD.group">

          		<label class="label-control">Select Centers</label>
          		<div ng-repeat="center in price_type.center track by $index">
          			<input type="checkbox" ng-if="add_PPD.city_id == center.city_id" ng-value="center.value"  ng-click="add_center_in_access_location(center.value)" ng-checked="price_type.center_ids.indexOf(center.value) > -1" >
          			<label class="label-control" ng-if="add_PPD.city_id == center.city_id">
          				@{{center.label}}
          			</label>
          		</div>
          	</div>

          	<div class="col-md-8 form-group" ng-show="add_PPD.group">
          		<label class="label-control">Select Center</label>
          		<select class="form-control" ng-model="add_PPD.center_id"
              ng-change="clear_group_ids()">
          			<option value="0">Select</option>
          			<option ng-repeat="center in price_type.center track by $index" 
          			value="@{{center.value}}" ng-if="add_PPD.city_id == center.city_id">
          				@{{center.label}}
          			</option>
          		</select>

          	</div>

          	<div class="col-md-8 form-group" ng-show="add_PPD.group" 
          	ng-if="add_PPD.group">

          		<label class="label-control">Select Groups</label>

          		<div ng-repeat="group in price_type.group track by $index">
          			<input type="checkbox" ng-if="add_PPD.center_id == group.center_id" ng-click="add_group_in_access_location(group.value)" >
          			<label class="label-control" ng-if="add_PPD.center_id == group.center_id">
          				@{{group.label}}
          			</label>
          		</div>

          	</div>
          	
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary"  ng-click="openAddPayPrice('price_type_modal')">
      			Next
      	  </button>

          <button type="button" class="btn btn-default" 
          ng-click="hide_data_modal('price_access')">Close</button>
        </div>
      </div>
      
    </div>
	</div>