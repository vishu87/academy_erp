<div class="modal fade in" id="company_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Company</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body small-form" >
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Company Name</label>
                        <input type="text" ng-model="companyData.company_name" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Contact Number</label>
                        <input type="text" ng-model="companyData.contact_no" class="form-control">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Address</label>
                        <input type="text" ng-model="companyData.address" class="form-control">
                    </div> 

                    <div class="col-md-4 form-group">
                        <label>GST</label>
                        <input type="text" ng-model="companyData.gst" class="form-control">
                    </div> 

                        <div class="col-md-4 form-group">
                            <label>State</label>
                            <select ng-model="companyData.state_id" name="state_id" class="form-control" ng-change="selectCity(companyData.state_id)" >
                                <option value="0">Select</option>
                                <option ng-repeat="state in state_city_center.state" 
                                ng-value="@{{state.value}}">@{{state.label}}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>City </label>
                            <select ng-model="companyData.state_city_id" name="state_city_id" class="form-control">
                                <option value="0">Select</option>
                                <option ng-repeat="city in cities" 
                                ng-value="@{{city.value}}">@{{city.label}}
                                </option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" ng-click="saveCompany()" ng-disabled="processing_req" >@{{companyData.id ? 'Update Company' : 'Save Company'}} <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
                </div>
            </div>
        </div>
    </div>
</div>