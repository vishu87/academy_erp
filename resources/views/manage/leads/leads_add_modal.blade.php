<div class="modal fade in" id="add-lead" role="dialog" data-backdrop="static" style="overflow: scroll;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Lead</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body small-form" >
                <form ng-submit="onSubmitLead(leadForm.$valid)" name="leadForm" novalidate> 
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="section-title">Lead Details</h4>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Name <span class="required">*</span></label>
                            <input type="text" ng-model="leadData.name" class="form-control" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>DOB</label>
                            <input type="text" ng-model="leadData.dob" class="form-control datepicker" ng-change="getAge()">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Mobile <span class="required">*</span></label>
                            <input type="text" ng-model="leadData.mobile" class="form-control" ng-pattern="/^[0-9]{10}$/" ng-pattern-err-type="PatternMobile" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Email </label>
                            <input type="text" ng-model="leadData.client_email" class="form-control">
                        </div>
                        <div class="col-md-3 form-group clear">
                            <label>Gender<br>
                            <label>
                                <input type="radio" convert-to-number ng-model="leadData.gender" value="1" > &nbsp;Male 
                            </label>&nbsp;&nbsp;
                            <label>
                                <input type="radio" convert-to-number ng-model="leadData.gender" value="2" > &nbsp;Female
                            </label>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Lead For</label>
                            <select ng-model="leadData.lead_for" class="form-control" convert-to-number>
                                <option value="">Select</option>
                                <option value="@{{product.value}}" ng-repeat="product in parameters.lead_for">@{{product.label}}</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Lead Source</label>
                            <select class="form-control" ng-model="leadData.lead_source" ng-change="getCampaignId()">
                                <option ng-repeat="source in parameters.lead_sources" ng-value="source.value">@{{source.label}}</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>School Name</label>
                            <input type="text" class="form-control" ng-model="leadData.school_name" />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Class Studying</label>
                            <input type="text" class="form-control" ng-model="leadData.class_studying" />
                        </div>

                        <div class="col-md-9 form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" ng-model="leadData.remarks" /></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <h4 class="section-title">Lead Location</h4>
                        </div>

                        <div class="col-md-3 form-group ">
                            <label>Locality</label>
                            <input type="text" ng-model="leadData.client_address" class="form-control">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>State</label>
                            <select class="form-control" ng-model="leadData.client_state_id" ng-change="getCities()">
                                <option value="0">Select</option>
                                <option ng-repeat="state in parameters.state" ng-value="state.value">@{{state.label}}</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                                    
                            <label>City</label>
                            <select class="form-control" ng-model="leadData.client_city_id">
                                <option value="0">Select</option>
                                <option ng-repeat="city in parameters.cities"  ng-value="city.value">@{{city.label}}</option>
                            </select>
                        </div>

                        

                        <div class="col-md-12">
                            <h4 class="section-title">Training Location</h4>
                        </div>

                        <div class="col-md-12 form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Training City</label>
                                    <select class="form-control" ng-model="leadData.city_id">
                                        <option >Select</option>
                                        <option ng-repeat="city in parameters.city" ng-value="city.value">@{{city.label}}</option>
                                    </select>
                                </div>

                                <div class="col-md-3" ng-show="leadData.city_id > 0" >
                                    <label>Center</label>
                                    <select class="form-control" ng-model="leadData.center_id">
                                        <option >Select</option>
                                        <option ng-repeat="center in parameters.center" ng-value="center.value" ng-if="center.city_id == leadData.city_id">@{{center.label}}</option>
                                    </select>
                                </div>
                                <div class="col-md-3" ng-show="leadData.city_id > 0">
                                    <label>Age Group </label>
                                    <select class="form-control" ng-model="leadData.group_id" >
                                        <option>Select</option>
                                        <option ng-repeat="age_group in parameters.group" ng-value="age_group.value" ng-if="age_group.center_id == leadData.center_id">@{{age_group.label}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="section-title">Status</h4>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Assigned To <span class="required">*</span></label>
                            <select class="form-control" ng-model="leadData.assigned_to" required>
                                <option>Select</option>
                                <option ng-repeat="member in parameters.members" ng-value="member.value">@{{member.label}}</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Lead Status <span class="required">*</span></label>
                            <select class="form-control" ng-change="checkReason(leadData.status)" ng-model="leadData.status" required>
                                <option>Select</option>
                                <option ng-repeat="st in parameters.status" ng-value="st.value">@{{st.label}}</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 form-group" ng-if="status_row.date_req == 1">
                            <label>@{{ status_row.action_date_name }} <span class="required">*</span></label>
                            <input type="text" ng-model="leadData.action_date" class="form-control datepicker" >
                        </div>

                        <div class="col-md-3 form-group" ng-if="status_row.reason_req == 1">
                            <label>Reason <span class="required">*</span></label>
                            <select class="form-control" ng-model="leadData.reason_id" convert-to-number>
                                <option ng-repeat="reason in parameters.reasons" value="@{{reason.id}}">@{{reason.reason}}</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Call made</label><br>
                            <label><input type="radio" ng-model="leadData.call_made" ng-value="1"> Yes</label>&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" ng-model="leadData.call_made" ng-value="0"> No</label>
                        </div>                       

                        <div class="col-md-12 form-group" >
                            <label>Call Note <span class="required" ng-if="status_row.call_note_req == 1">*</span></label>
                            <textarea ng-model="leadData.call_note" class="form-control" ng-required="status_row.call_note_req == 1"></textarea>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit" ng-disabled="processing_req">Submit <span ng-show="processing_req" class="spinner-border spinner-border-sm"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>