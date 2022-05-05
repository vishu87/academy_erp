<div class="modal" id="add_category_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Category</h4>
                <button type="button" class="close" ng-click="hide_data_modal('add_category_modal');">&times;</button>
            </div>
            <div class="modal-body">

				<div class="form-group">
					<label class="label-control">Category</label>
					<input type="text" class="form-control" ng-model="Category.category_name">
				</div>

            </div>
            <div class="modal-footer">
                <button class="btn-primary btn" ng-click="addCategory()" ng-disabled="processing" >
                    Add <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
                </button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="edit_category_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category (@{{Category.category_name}})</h4>
                <button type="button" class="close" ng-click="hide_data_modal('edit_category_modal');">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" ng-model="Category.category_name">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn" ng-click="addCategory()" ng-disabled="processing">
                    Update <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
                </button>
                <button class="btn-light btn" ng-click="disableCategory()" ng-disabled="disableProcessing">
                    @{{ Category.inactive == 0 ? 'Disable' : 'Enable' }} <span ng-show="disableProcessing" class="spinner-border spinner-border-sm"></span>
                </button>
                <button class="btn-danger btn" ng-click="deleteCategory()" ng-disabled="deleteProcessing">
                    Delete <span ng-show="deleteProcessing" class="spinner-border spinner-border-sm"></span>
                </button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="add_pay_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sub Category</h4>
                <button type="button" class="close" ng-click="hide_data_modal('add_pay_modal');">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="label-control">Name</label>
                    <input type="text" class="form-control" ng-model="add_cat_item.name">
                </div>
                <div class="form-group">
                    <label class="label-control">HSN Code</label>
                    <input type="text" class="form-control" ng-model="add_cat_item.hsn_code">
                </div>

                <div class="form-group" ng-if="is_sub_type == 1">
                    <label class="label-control">Months</label>
                    <input type="text" class="form-control" ng-model="add_cat_item.months">
                </div>

                <div class="form-group">
                    <label class="label-control">Tax (%)</label>
                    <input type="text" class="form-control" ng-model="add_cat_item.tax">
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn-primary btn" ng-click="add()" ng-disabled="processing">
                    Add <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
                </button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="edit_category_item" role="dialog">
	<div class="modal-dialog">
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="modal-title">Edit Type</h4>
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
          	<div class="form-group">
          		<label class="label-control">Name</label>
          		<input type="text" ng-model="edit_cat_item.name" class="form-control">
          	</div>
            <div class="form-group">
                <label class="label-control">HSN Code</label>
                <input type="text" class="form-control" ng-model="edit_cat_item.hsn_code" class="form-control">
            </div>
            <div class="form-group" ng-if="edit_cat_item.months">
                <label class="label-control">Months</label>
                <input type="text" class="form-control" ng-model="edit_cat_item.months">
            </div>
            <div class="form-group">
                <label class="label-control">Tax (%)</label>
                <input type="text" class="form-control" ng-model="edit_cat_item.tax">
            </div>

	    </div>
	    <div class="modal-footer">
        	<button type="button" class="btn btn-primary" ng-click="update(edit_cat_item)" ng-disabled="processing">
                Update <span ng-show="updateProcessing" class="spinner-border spinner-border-sm"></span>
            </button>
        	<button type="button" class="btn btn-danger" ng-click="delete(edit_cat_item.id)" ng-disabled="deleteProcessing">
                Delete <span ng-show="deleteProcessing" class="spinner-border spinner-border-sm"></span>
            </button>
	     	<button type="button" class="btn btn-default" data-dismiss="modal">
                Close
            </button>
	    </div>

	</div>
	  
	</div>
</div>