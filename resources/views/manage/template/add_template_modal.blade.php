        <div class="modal fade in" id="add-template" role="dialog" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Template</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>

                    <div class="modal-body">
                        <form ng-submit="onSubmit(addForm.$valid)" name="addForm" novalidate>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Template For <span class="text-danger">*</span></label><br>
                                    <label><input type="radio" ng-model="formData.type" value="1" convert-to-number> &nbsp;Promotional SMS</label>&nbsp;&nbsp;
                                    <label><input type="radio" ng-model="formData.type" value="2" convert-to-number> &nbsp;Transactional SMS</label>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <input type="text" ng-model="formData.name" class="form-control" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>DLT Sender ID <span class="text-danger">*</span></label>
                                    <input type="text" ng-model="formData.dlt_sender_id" class="form-control" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>DLT Template ID <span class="text-danger">*</span></label>
                                    <input type="text" ng-model="formData.dlt_template_id" class="form-control" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>DLT PE ID <span class="text-danger">*</span></label>
                                    <input type="text" ng-model="formData.dlt_pe_id" class="form-control" required>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label>Content  <span class="text-danger">*</span></label>
                                    <input type="text" ng-model="formData.template" class="form-control" required>
                                </div>

                            </div>
                            <div style="margin-top:20px">
                                <button class="btn btn-primary" ng-disabled="processing">Submit <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>