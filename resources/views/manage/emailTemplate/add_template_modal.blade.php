        <div class="modal fade in" id="add-template" role="dialog" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Email Template</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>

                    <div class="modal-body">
                        <form ng-submit="onSubmit(addForm.$valid)" name="addForm" novalidate>
                            <div class="row">
                                
                                <div class="col-md-6 form-group">
                                    <label>Template Name <span class="text-danger">*</span></label>
                                    <input type="text" ng-model="formData.template_name" class="form-control" required>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Content <span class="text-danger">*</span></label>
                                    <textarea type="text" ng-model="formData.content" class="form-control" required>
                                    </textarea>
                                </div>

                            </div>
                            <div style="margin-top:20px">
                                <button type="button" ng-click="onSubmit()" class="btn btn-primary" ng-disabled="processing">@{{(formData.id) ? "Update" : "Create" }} <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>