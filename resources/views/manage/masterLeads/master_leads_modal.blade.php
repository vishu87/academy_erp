<div class="modal" id="lead_for_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@{{(lead_for.id) ? "Update" : "Add" }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Label<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="lead_for.label" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Slug<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="lead_for.slug" required class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" ng-click="submitLeadFor()" class="btn btn-primary" ng-disabled="leadForProcessing">@{{(lead_for.id) ? "Update" : "Create" }} <span ng-show="leadForProcessing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="lead_status_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Status Value<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="lead_sts.status_value" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Data Name<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="lead_sts.action_date_name" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Data Required<span class="text-danger"> *</span></label>
                    <select ng-model="lead_sts.date_req" required class="form-control" convert-to-number>
                        <option>Select</option>
                        <option value="0">Yes</option>
                        <option value="1">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Call Note Required<span class="text-danger"> *</span></label>
                    <select ng-model="lead_sts.call_note_req" required class="form-control" convert-to-number>
                        <option>Select</option>
                        <option value="0">Yes</option>
                        <option value="1">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Reason Required<span class="text-danger"> *</span></label>
                    <select ng-model="lead_sts.reason_req" required class="form-control" convert-to-number>
                        <option>Select</option>
                        <option value="0">Yes</option>
                        <option value="1">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Color<span class="text-danger"> *</span></label>
                    <input type="color" ng-model="lead_sts.color" required class="form-control">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" ng-click="submitLeadStatus()" class="btn btn-primary" ng-disabled="leadstatusProcessing"> Update <span ng-show="leadstatusProcessing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="lead_reason_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@{{(lead_reason.id) ? "Update" : "Add" }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Reason<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="lead_reason.reason" required class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" ng-click="submitLeadReason()" class="btn btn-primary" ng-disabled="leadReasonProcessing">@{{(lead_reason.id) ? "Update" : "Create" }} <span ng-show="leadReasonProcessing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>

<div class="modal" id="lead_source_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@{{(lead_source.id) ? "Update" : "Add" }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Source<span class="text-danger"> *</span></label>
                    <input type="text" ng-model="lead_source.source" required class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" ng-click="submitLeadSource()" class="btn btn-primary" ng-disabled="leadSourceProcessing">@{{(lead_source.id) ? "Update" : "Create" }} <span ng-show="leadSourceProcessing" class="spinner-border spinner-border-sm"></span></button>
            </div>
      </div>
    </div>
</div>