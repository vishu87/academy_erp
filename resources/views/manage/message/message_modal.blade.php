<div class="modal fade in" id="messageForm" role="dialog" ng-controller="sendMessageCtrl">
	<div class="modal-dialog modal-xl">
	    <div class="modal-content">
	        <div class="modal-header">
	            <h4 class="modal-title">Send Message</h4>
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
	        </div>
	        <div class="modal-body">
            	<div class="row">
	            	<div class="col-md-6">
		            	<form ng-submit="postMessage(msgForm.$valid)" name="msgForm" novalidate>
			            	<div class="form-group">
								<label>Communication Type </label><br>
								<button type="button" class="btn" ng-class="msgData.type == 1 ? 'btn-primary' :'btn-light'" ng-click="msgData.type = 1" > SMS </button>&nbsp;&nbsp;&nbsp;
								<button type="button" class="btn" ng-class="msgData.type == 2 ? 'btn-primary' :'btn-light'" ng-click="msgData.type = 2" > EMAIL </button>
							</div>

							<div ng-if="msgData.type == 1">
								<div class="row">
									<div class="col-md-6 form-group">
										<label>SMS Type</label><br>
										<label>
											<input type="radio" ng-model="msgData.sms_type" value="1"> &nbsp;Promotional
										</label>
										&nbsp;&nbsp;
										<label>
											<input type="radio" ng-model="msgData.sms_type" value="2"> &nbsp;Transactional
										</label>
									</div>
								</div>

								<div class="form-group">
		                            <label>SMS Template <span class="text-danger">*</span></label>
		                            <select ng-model="msgData.template_id" class="form-control" ng-change="templateChange()" required>
		                            	<option value="">Select</option>
		                            	<option value="@{{template.value}}" ng-repeat="template in sms_templates" ng-if="template.type == msgData.sms_type">@{{template.label}}</option>
		                            </select>              
		                        </div>
							</div>

							<div ng-if="msgData.type == 2">

								<div class="form-group">
		                            <label>Email Template <span class="text-danger">*</span></label>
		                            <select ng-model="msgData.template_id" class="form-control" ng-change="templateChange()" required>
		                            	<option value="">Select</option>
		                            	<option value="@{{template.value}}" ng-repeat="template in email_templates">@{{template.label}}</option>
		                            </select>              
		                        </div>

								<div class="form-group">
									<label> Subject <span class="text-danger">*</span></label>
									<input type="text" ng-model="msgData.subject" class="form-control" required="">
								</div>

							</div>

							<div class="row" style="margin-top:20px">
								<div class="col-md-4 form-group">
									<label>Variable 1 </label><br>
									<input type="text" class="form-control" ng-model="msgData.variable1">
								</div>
								<div class="col-md-4 form-group">
									<label>Variable 2 </label><br>
									<input type="text" class="form-control" ng-model="msgData.variable2">
								</div>
								<div class="col-md-4 form-group">
									<label>Variable 3 </label><br>
									<input type="text" class="form-control" ng-model="msgData.variable3">
								</div>
							</div>

			                <div class="modal-footer">
			                    <button type="submit" class="btn btn-primary"  ng-disabled="processing_req"> Send <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
			                </div>
		            	</form>
		            </div>
		            <div class="col-md-6">
		            	<b>Preview</b>
		            	<div style="background: #EEE; border-radius: 5px; padding: 10px;" id="content_msg">
		            		
		            	</div>
		            </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>