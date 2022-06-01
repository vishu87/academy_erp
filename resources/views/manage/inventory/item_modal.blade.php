<div class="modal fade in" id="items-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body small-form" >
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Name</label>
                        <input type="text" ng-model="itemData.item_name" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Min Quantity</label>
                        <input type="number" ng-model="itemData.min_quantity" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Unit</label>
                        <select  ng-model="itemData.unit_id" class="form-control" convert-to-number>
                            <option value="@{{unit.id}}" ng-repeat="unit in units">@{{unit.unit}}</option>  
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" ng-click="saveItem()" ng-disabled="processing_req" >@{{(itemData.id) ? 'Update Item' : 'Save Item'}} <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
                </div>
            </div>
        </div>
    </div>
</div>