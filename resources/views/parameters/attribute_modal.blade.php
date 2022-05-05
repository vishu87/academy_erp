<div class="modal fade in" id="attribute-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Attribute</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body small-form" >
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Name of attribute</label>
                        <input type="text" ng-model="attrData.attribute_name" class="form-control">
                    </div>
                </div>
                <button type="button" ng-click="saveAttribute()" class="btn btn-primary" ng-disabled="processing">Save Attribute <span ng-show="processing" class="spinner-border spinner-border-sm"></span></button>
            </div>
        </div>
    </div>
</div>