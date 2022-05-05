<div class="modal fade in" id="category-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body small-form" >
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Name of category</label>
                        <input type="text" ng-model="formData.category_name" class="form-control">
                    </div>
                </div>
                <button type="button" ng-click="saveCategory()" class="btn btn-primary" ng-disabled="processing">Save Category <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
            </div>
        </div>
    </div>
</div>