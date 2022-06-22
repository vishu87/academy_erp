<div class="modal" id="client_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@{{(formData.id) ? "Update" : "Add" }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">

            	<div class="form-group">
                    <label>Code<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="formData.code" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Name<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="formData.name" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Email<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="formData.email" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Phone <span class="text-danger"> *</span> </label>
                    <input type="text" ng-model="formData.phone" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Address <span class="text-danger"> *</span> </label>
                    <input type="text" ng-model="formData.address" required class="form-control">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" ng-click="submit()" class="btn btn-primary" ng-disabled="processing">@{{(formData.id) ? "Update" : "Create" }} <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>