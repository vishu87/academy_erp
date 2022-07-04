<div class="modal fade in" id="holiday-type-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body small-form" >
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Name</label>
                        <input type="text" ng-model="formData.name" class="form-control">
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Date</label>
                        <input type="text" ng-model="formData.date" class="form-control datepicker">
                    </div>
                </div>
                <button type="button" ng-click="submit()" class="btn btn-primary" ng-disabled="processing">Submit <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
            </div>
        </div>
    </div>
</div>