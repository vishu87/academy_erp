<div class="modal fade in" id="items-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="modal-body small-form" >
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>item</label>
                        <input type="text" ng-model="itemData.item_name" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Min Quantity</label>
                        <input type="text" ng-model="itemData.min_quantity" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Unit</label>
                        <select  ng-model="itemData.unit_id" class="form-control" convert-to-number>
                            <option value="@{{unit.id}}" ng-repeat="unit in units">@{{unit.unit}}</option>  
                        </select>
                    </div>
                </div>
                <button type="button" ng-click="saveItem()" class="btn btn-info">@{{(itemData.id) ? 'Update Item' : 'Save Item'}}</button>
            </div>
        </div>
    </div>
</div>