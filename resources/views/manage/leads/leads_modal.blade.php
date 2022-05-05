<div class="modal fade in" id="sub_call_note" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="modal-title">Demo Schedule Details</h4>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                </div>
            </div>
            <div class="modal-body" style="max-height:500px; overflow-y:auto">
                <form ng-submit="submitScheduleData(ScheduleForm.$valid)" name="ScheduleForm" novalidate>
                    
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Training City <span class="error">*</span></label>
                            <select class="form-control" ng-change="switchCenters(openLead.city_id)" ng-model="openLead.city_id" required>
                                <option >Select</option>
                                <option ng-repeat="city in cities" ng-value="city.id">@{{city.city_name}}</option>
                            </select>
                        </div>
                        

                        <div class="col-md-3 form-group" ng-show="openLead.city_id != -1" >
                            <label>Center <span class="error">*</span></label>
                            <select class="form-control" ng-change="switchAgeGroups(openLead.center_id)" ng-model="openLead.center_id">
                                <option >Select</option>
                                <option ng-repeat="center in center_list" ng-value="center.id">@{{center.center_name}}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group" ng-show="openLead.city_id != -1">
                            <label>Age Group </label>
                            <select class="form-control" ng-model="openLead.age_group" ng-change="sms_text_show = ''">
                                <option>Select</option>
                                <option ng-repeat="age_group in age_groups" ng-value="age_group.id">@{{age_group.group_name}}</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="row">

                        <div class="col-md-3 form-group" ng-show="openLead.city_id != -1">
                            <label>RA Trial </label>
                            <br><input type="checkbox" ng-model="openLead.is_trial" value="1" ng-change="sms_text_show = ''" />
                        </div>
                        <div class="col-md-6 form-group" ng-show="openLead.city_id != -1">
                            <label>Send Regular Training SMS </label>
                            <br><input type="checkbox" ng-model="openLead.normal_message" value="1" ng-change="sms_text_show = ''" />
                        </div>

                        <div class="col-md-3 form-group clear" ng-show="openLead.city_id != -1 && openLead.is_trial && !openLead.normal_message">
                            <label>Time for trial</label>
                            <input type="text" class="form-control" ng-model="openLead.trial_time" ng-change="sms_text_show = ''" />
                        </div>
                        <div class="col-md-3 form-group" ng-show="openLead.city_id != -1 && openLead.is_trial && !openLead.normal_message">
                            <label>Venue for trial</label>
                            <input type="text" class="form-control" ng-model="openLead.trial_venue" ng-change="sms_text_show = ''" />
                        </div>

                        <div class="col-md-3 form-group" ng-show="openLead.city_id != -1 && openLead.is_trial && !openLead.normal_message">
                            <label>City for trial</label>
                            <select class="form-control" ng-model="openLead.trial_city_id">
                                <option>Select</option>
                                <option ng-repeat="city in ra_cities" ng-value="city.id">@{{city.city_name}}</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group" >
                            <div style="margin-top: 25px" >
                                <button class="btn btn-primary" ladda="processing" id="check_schedule_edit">Check Schedule</button>
                            </div>
                        </div>
                    </div>
                    
                </form>

                <p ng-bind-html="sms_text_show" style="padding-top: 20px"></p>

                <div style="margin-top: 15px" ng-show="confirm_show && sms_text_show">
                    <button class="btn btn-primary" ladda="processing" ng-click="confirmDemo()">Confirm Demo</button>
                    <button class="btn btn-default" ng-click="cancelDemo()">Cancel</button>
                </div>
                
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="sub_not_fresh_lead" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="modal-title">Demo Schedule Details</h4>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                </div>
            </div>
            <div class="modal-body" style="max-height:500px; overflow-y:auto">
                <form ng-submit="onSubmit(ScheduleLead.$valid)" name="ScheduleLead" novalidate>
                    
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Training City <span class="error">*</span></label>
                            <select class="form-control" ng-change="switchCenters(leadData.city_id)" ng-model="leadData.city_id" required>
                                <option >Select</option>
                                <option ng-repeat="city in cities" ng-value="city.id">@{{city.city_name}}</option>
                            </select>
                        </div>
                        

                        <div class="col-md-3 form-group" ng-show="leadData.city_id != -1" >
                            <label>Center <span class="error">*</span></label>
                            <select class="form-control" ng-change="switchAgeGroups(leadData.center_id)" ng-model="leadData.center_id">
                                <option >Select</option>
                                <option ng-repeat="center in center_list" ng-value="center.id">@{{center.center_name}}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group" ng-show="leadData.city_id != -1">
                            <label>Age Group </label>
                            <select class="form-control" ng-model="leadData.age_group" >
                                <option>Select</option>
                                <option ng-repeat="age_group in age_groups" ng-value="age_group.id">@{{age_group.group_name}}</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: 15px" ng-show="!confirm_show">
                        <button class="btn btn-primary" ng-click="leadData.demo_schedule_added = true" ladda="processing">Check Schedule</button>
                    </div>
                    <p ng-bind-html="sms_text_show"></p>

                    <div style="margin-top: 15px" ng-show="confirm_show">
                        <button class="btn btn-primary" ladda="processing">Confirm Demo</button>
                        <button class="btn btn-default" ng-click="cancelDemo()">Cancel</button>
                    </div>
                </form>

                
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-modal-lg in" id="bulk_upload_lead" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="modal-title">Bulk Upload Leads</h4>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                </div>
            </div>
            <div class="modal-body" style="max-height:500px; overflow-y:auto">
                <div ng-show="errMsg" style="margin-top: 10px;">
                    <div class="alert alert-danger">@{{errMsg}}</div>
                </div>
                <button type="button" class="btn btn-info upload-btn" ngf-select="uploadLeads($file)" ng-hide="bulk_upload_processing" data-style="expand-right" >Bulk Upload Leads</button>

                <div class="progress" ng-show="bulk_upload_processing">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="@{{uploading_percentage}}" aria-valuemin="20" aria-valuemax="100" style="width: @{{uploading_percentage > 15 ? uploading_percentage:15;}}%">
                      <span ng-show="uploading_percentage && uploading_percentage != 100">Uploading file @{{uploading_percentage > 15 ? uploading_percentage:15;}}%</span>
                      <span ng-show="uploading_percentage == 100">Leads are uploading ...</span>
                    </div>
                </div>

                <div ng-show="bulk_upload_processing" style="margin-top: 10px;font-size: 12px;">Do not close this tab or refresh this page untill leads are uploading</div>

                <div style="margin-top: 10px">
                    
                <a href="lead/Lead_Upload_Format.csv" style="font-size: 12px" target="_blank">Download Format</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade in" id="showNumber" role="dialog">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <div class="modal-body">
                <div style="font-size:32px;" class="text-center">
                    @{{mobile_show}}
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="transferLead" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="modal-title">Transfer Lead</h4>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div ng-show="!openLead.loading && !openLead.edit" >
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <strong>Name</strong> : @{{openLead.name}}
                            </td>
                            <td>
                                <strong>Age</strong> : @{{openLead.age}}
                            </td>
                            <td>
                                <strong>Mobile</strong> : @{{openLead.mobile}}
                            </td>
                            <td>
                                <strong>Locality</strong> : @{{openLead.client_address}}
                            </td>
                            <td>
                                <strong>City</strong> : @{{openLead.client_city_name}}  @{{openLead.client_state_name}} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Center</strong> : @{{openLead.center_name}}
                            </td>
                            <td>
                                <strong>Age Group</strong> : @{{openLead.group_name}}
                            </td>
                            <td>
                                <strong>Assigned To</strong> : @{{openLead.assigned_member}}
                            </td>
                            <td>
                                <strong>Status</strong> : @{{openLead.status_value}}
                            </td>
                            <td>
                                <strong>Lead Source</strong> : @{{openLead.source}}
                                <span ng-if="openLead.lead_cost">(Rs @{{openLead.lead_cost}})</span>
                            </td>
                        </tr>
                        
                    </table>
                </div>
                <form ng-submit="submitTransferLead()">
                    
                    <div class="row">
                        <div class="col-md-3 form-group">
                            
                            <label>Training City <span class="error">*</span></label>
                            <select class="form-control" ng-change="switchTransferCenters(transferLeadData.city_id)" ng-model="transferLeadData.city_id" required>
                                <option >Select</option>
                                <option ng-repeat="city in citiesfortransfer" ng-value="city.id">@{{city.city_name}}</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 form-group" ng-show="transferLeadData.city_id != -1" >
                            <label>Center</label>
                            <select class="form-control" ng-change="switchTransferAgeGroups(transferLeadData.center_id)" ng-model="transferLeadData.center_id">
                                <option >Select</option>
                                <option ng-repeat="center in center_list" ng-value="center.id">@{{center.center_name}}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group" ng-show="transferLeadData.city_id != -1" >
                            <label>Age Group </label>
                            <select class="form-control" ng-model="transferLeadData.age_group">
                                <option>Select</option>
                                <option ng-repeat="age_group in age_groups" ng-value="age_group.id">@{{age_group.group_name}}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" ladda="processing" style="margin-top: 23px;">Confirm</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="advance-options" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="modal-title">Advanced Options</h4>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form ng-submit="addPackage(packageForm.$valid)" name="packageForm" novalidate>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Select Student Category <span class="error">*</span></label>
                            <select ng-model="openLead.category_id" ng-change="loadPackages()" class="form-control" required convert-to-number>
                                <option value="">Select</option>
                                <option ng-repeat="category in categories" value="@{{category.category_id}}">@{{category.category_name}}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>On Subscription Discount(%) <span class="error">*</span></label>
                            <input type="text" ng-model="openLead.special_discount" class="form-control" required>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Times Valid <span class="error">*</span></label>
                            <input type="text" ng-model="openLead.valid_time" class="form-control" required>
                        </div>

                        <div class="col-md-3"> 
                            <label>Package Applicable On</label>
                            <div>
                                
                                <label>
                                    <input type="checkbox" ng-true-value="1" ng-model="openLead.package_3m"> &nbsp;3M&nbsp;&nbsp;
                                </label>
                                <label>
                                    <input type="checkbox" ng-true-value="1" ng-model="openLead.package_6m"> &nbsp;6M&nbsp;&nbsp;
                                </label>
                                <label>
                                    <input type="checkbox" ng-true-value="1"  ng-model="openLead.package_12m"> &nbsp;12M&nbsp;&nbsp;
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" style="margin-top: 23px">Update/Save</button>
                        </div>
                    </div>
                    
                </form>
                

                <div class="row" ng-show="openLead.category_id">
                        <div class="col-md-12">
                            
                            <strong>@{{group.group_name}} (@{{group.group_type}}) - Sub Fee Calculation</strong>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>@{{category.category_name}}</th>
                                    <th ng-repeat="month in group.months">
                                        @{{month.value}} M
                                    </th>
                                </tr>
                                <tr style="background: #F4CCCC">
                                    <td>
                                        City Base Price
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Package Discount(%)</i></span>
                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="baseFee(group.group_type_id, month.value)"></span>

                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i ng-bind="city['G'+group.group_type_id+'_M'+month.value] ? city['G'+group.group_type_id+'_M'+month.value] : 0"></i></span>
                                    </td>
                                </tr>

                                <tr style="background: #EA9999">
                                    <td>
                                        Package Fee
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Center Discount(%)</i></span>
                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="cityAmount(group, month.value)"></span>

                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>@{{center['G'+group.group_type_id+'_DISCOUNT'] ? center['G'+group.group_type_id+'_DISCOUNT'] : 0}}</i></span>
                                    </td>
                                </tr>

                                <tr style="background: #E06666">
                                    <td>
                                        Center Fee
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Category Discount(%)</i></span>
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Batch Discount(%)</i></span>
                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="centerAmount(group, center, month.value)"></span>

                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>@{{ center['C'+category.category_id+'_DISCOUNT'] ? center['C'+category.category_id+'_DISCOUNT'] : 0 }}</i></span>

                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>@{{group.discount ? group.discount : 0}}</i></span>
                                    </td>
                                </tr>

                                <tr style="background: #CC0000;color: #fff">
                                    <td>
                                        Total Effective Discount(Rs)
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Total Effective Discount(%)</i></span>

                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="totalEffectiveAmount(group, center, category.category_id, month.value)"></span>

                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i ng-bind="totalEffectiveDiscount(group, center, category.category_id, month.value)">10</i></span>
                                        
                                    </td>
                                </tr>

                                <tr style="background: #FFF2CC">
                                    <td>
                                        Sub Fee (after discount)
                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="finalAmount(group, center, category.category_id, month.value)"></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Final Fee Structure (@{{category.category_name}})</th>
                                    <th ng-repeat="month in group.months">
                                        @{{month.value}} M
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        Reg Fee
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Reg Fee GST(%)</i></span>
                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="regFee(group.group_type_id)"></span>
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i ng-bind="city.reg_fee_tax"></i></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Sub Fee
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Sub Fee GST(%)</i></span>
                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="finalAmount(group, center, category.category_id, month.value)"></span>
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i ng-bind="city.sub_fee_tax"></i></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Kit Fee
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i>Kit Fee GST(%)</i></span>
                                    </td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="kitFee(group.group_type_id)"></span>
                                        <span style="display: block;text-align:right; margin-top: 10px;font-size: 10px"><i ng-bind="city.kit_fee_tax"></i></span>
                                    </td>
                                </tr>

                                <tr style="background: #B6D7A8">
                                    <td>Total Fee</td>
                                    <td ng-repeat="month in group.months">
                                        <span style="display: block" ng-bind="netAmount(group, center, category.category_id, month.value)"></span>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="messageForm" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-small">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="row">
                            <div class="col-md-8">
                                
                                <h4 class="modal-title">Message Details</h4>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                
                            </div>
                        </div>
                    </div>


                    <div class="modal-body">
                        <div ng-show="selectedLeads.length > 0">
                            <form ng-submit="postMessage(msgForm.$valid)" name="msgForm" novalidate>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Communication Type </label><br>
                                        <label>
                                            <input type="checkbox" ng-model="formData.send_type[1]"> &nbsp;SMS
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="checkbox" ng-model="formData.send_type[2]"> &nbsp;Email
                                        </label>

                                    </div>
                                </div>

                                <div class="row" ng-if="formData.send_type[1]">
                                    <div class="col-md-6 form-group">
                                        <label>SMS Type</label><br>
                                        <label>
                                            <input type="radio" ng-required="formData.send_type == 1" ng-model="formData.sms_type" value="1"> &nbsp;Promotional
                                        </label>
                                        &nbsp;&nbsp;
                                        <label>
                                            <input type="radio" ng-required="formData.send_type == 1" ng-model="formData.sms_type" value="2"> &nbsp;Transactional
                                        </label>
                                    </div>
                                </div>

                                

                                <div ng-if="formData.send_type[1]">
                                    <label>SMS Content</label>
                                    <div class="form-group">
                                        <textarea class="form-control" ng-model="formData.sms_content"></textarea>
                                    </div>
                                    
                                </div>

                                <div ng-if="formData.send_type[2]">
                                    <label> Subject</label>
                                    <div class="form-group">
                                        <input type="text" ng-model="formData.subject" class="form-control" required>
                                    </div>
                                </div>

                                <div ng-if="formData.send_type[2]">
                                    <label>Email Content</label>
                                    <div class="form-group">
                                        <trix-editor angular-trix class="trix-content"  ng-model="formData.content"></trix-editor>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Demo Check</label><br>
                                        <input type="checkbox" ng-model="formData.demo_check" value="1">
                                    </div>
                                    <div class="col-md-4 form-group" ng-show="formData.demo_check && formData.send_type[1]">
                                        <label>Test Number</label>
                                        <input type="text" ng-model="formData.demo_mobile" class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group" ng-show="formData.demo_check && formData.send_type[2]">
                                        <label>Test Email</label>
                                        <input type="text" ng-model="formData.demo_email" class="form-control">
                                    </div>

                                </div>

                                <div style="margin-top: 5px">
                                    <button class="btn btn-primary" ladda="processing">Send</button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
</div>