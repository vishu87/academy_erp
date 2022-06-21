<div class="modal fade in" id="messageForm" role="dialog" >
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	        <div class="modal-header">
	            <h4 class="modal-title">Message Details @{{send_type_check}}</h4>
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
	        </div>

	        <div class="modal-body">
	            <div ng-show="students.length > 0">
	            	<form ng-submit="postMessage(msgForm.$valid)" name="msgForm" novalidate>
		            	<div class="form-group">
							<label>Communication Type </label><br>
							<button class="btn btn-primary" ng-click="changeType(1)" > SMS </button>&nbsp;&nbsp;&nbsp;
							<button class="btn btn-primary" ng-click="changeType(2)" > Email </button>
						</div>

						<div ng-if="send_type_check == 1">
							<div class="row">
								<div class="col-md-6 form-group">
									<label>SMS Type</label><br>
									<label>
										<input type="radio" ng-model="formData.sms_type" ng-change="popTemplateList()" value="1"> &nbsp;Promotional
									</label>
									&nbsp;&nbsp;
									<label>
										<input type="radio" ng-change="popTemplateList()" ng-model="formData.sms_type" value="2"> &nbsp;Transactional
									</label>
								</div>
							</div>

							<div class="form-group">
	                            <label>SMS Template <span class="text-danger">*</span></label>
	                            <select ng-model="formData.template_id"  class="form-control" required>
	                            	<option value="">Select</option>
	                            	<option value="@{{template.id}}" ng-repeat="template in template_lists">@{{template.template}}</option>
	                            </select>              
	                        </div>

	                        <div class="row" style="margin-top:20px">
								<div class="col-md-4 form-group">
									<label>Variable 1 </label><br>
									<input type="text" class="form-control" ng-model="formData.variable1">
								</div>
								<div class="col-md-4 form-group">
									<label>Variable 2 </label><br>
									<input type="text" class="form-control" ng-model="formData.variable2">
								</div>
								<div class="col-md-4 form-group">
									<label>Variable 3 </label><br>
									<input type="text" class="form-control" ng-model="formData.variable3">
								</div>
							</div>
						</div>

						<div ng-if="send_type_check == 2 ">

							<div class="form-group">
	                            <label>EMAIL Template <span class="text-danger">*</span></label>
	                            <select ng-model="formData.template_id"  class="form-control" required>
	                            	<option value="">Select</option>
	                            	<option value="@{{template.id}}" ng-repeat="template in email_templates">@{{template.template_name}}</option>
	                            </select>              
	                        </div>

							<label> Subject</label>
							<div class="form-group">
								<input type="text" ng-model="formData.subject" class="form-control" required>
							</div>
						</div>


<!-- 						<div class="row">
							<div class="col-md-4" >
								<label>Demo Check</label>
								<input type="checkbox" class="form-group" ng-model="formData.demo_check" value="1">
							</div>
							<div class="col-md-4 form-group" ng-show="formData.demo_check && formData.send_type">
								<label>Test Number</label>
								<input type="text" ng-model="formData.demo_mobile" class="form-control">
							</div>
							<div class="col-md-4 form-group" ng-show="formData.demo_check && formData.send_type">
								<label>Test Email</label>
								<input type="text" ng-model="formData.demo_email" class="form-control">
							</div>
						</div> -->
		                <div class="modal-footer">
		                    <button type="submit" class="btn btn-primary"  ng-disabled="processing_req"> Send <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
		                </div>
	            	</form>
				</div>	          
	        </div>
	    </div>
	</div>
</div>