<div class="modal" id="coupon_type_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="modal-title">Coupon</h4>
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
  	  <div class="modal-body">
        
        <div class="filters" style="border-bottom: 1px solid #EEE; margin-bottom: 20px;">
          <form name="filterForm" ng-submit="" novalidate>
            <div class="row" style="font-size: 14px">
              <div class="col-md-6 form-group">
                <label class="label-control">Category</label>
                <select class="form-control" ng-model="formData.pay_type_cat_id" convert-to-number>
                  <option value="">Select</option>
                  <option ng-repeat="cat in price_type.pay_type_cat" value="@{{cat.id}}">@{{cat.category_name}}</option>
                </select>
              </div>

              <div class="col-md-6 form-group" ng-if="formData.pay_type_cat_id != 0">
                <label class="label-control">Sub Category</label>
                <select class="form-control" ng-model="formData.pay_type_id" convert-to-number>
                  <option value="">Select</option>
                  <option ng-repeat="type in price_type.pay_type" value="@{{type.id}}" ng-if="type.category_id == formData.pay_type_cat_id">@{{type.name}}</option>
                </select>
              </div>
            </div>
          </form>
        </div>

	      <div class="row" ng-if="formData.pay_type_id || formData.pay_type_cat_id == 0">
        	<div class="col-md-4 form-group">
        		<label>Dicount Code <span class="text-danger">*</span></label>
        		<input type="text" class="form-control" ng-model="formData.code" required="required">
        	</div>
        	<div class="col-md-4 form-group">
        		<label>Discount type <span class="text-danger">*</span></label>
            <select class="form-control" ng-model="formData.discount_type" required="required" convert-to-number >
              <option value="1">Percentage (%)</option>
              <option value="2">Amount</option>
            </select>
        	</div>
          <div class="col-md-4 form-group">
            <label>Discount <span class="text-danger">*</span></label>
            <input type="text" class="form-control" ng-model="formData.discount" required="required">
          </div>

          <div class="col-md-4 form-group">
            <label>Expiry Date </label>
            <input type="text" class="form-control datepicker" ng-model="formData.expiry_date">
          </div>

	      </div>
  	    </div>
  	    <div class="modal-footer" ng-if="formData.pay_type_id || formData.pay_type_cat_id == 0">
  	      
          <button type="submit" class="btn btn-primary" ng-click="addCoupon(add_PPD)" ng-disabled="couponProcessing">
            Submit
            <span ng-show="couponProcessing" class="spinner-border spinner-border-sm"></span>
          </button>
  	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

  	    </div>
	  </div>
	</div>
</div>


  <div class="modal" id="location_access" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Access Rights Coupon</h4>
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
              <div ng-repeat="center in all_center_list track by $index">

                <input type="checkbox" ng-if="access_location_data.city_id == center.city_id"
                ng-value="center.value" 
                ng-click="add_center_in_access_location(center.value)"
                ng-checked="access_location_data.center_ids.indexOf(names.id) > -1" >
                
                <label class="label-control" 
                ng-if="access_location_data.city_id == center.city_id">
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
                <input type="checkbox"
                ng-if="access_location_data.center_id == group.center_id"
                ng-click="add_group_in_access_location(group.value)" >
                
                <label class="label-control " 
                ng-if="access_location_data.center_id == group.center_id">
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