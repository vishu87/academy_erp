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