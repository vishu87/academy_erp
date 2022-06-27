<div class="modal" id="contact_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tax Setting</h4>
                <button type="button" class="close" ng-click="hideModal('contact_modal');"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" ng-model="gst.name">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>PAN No</label>
                            <input type="text" class="form-control" ng-model="gst.pan_no">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Registered Office</label>
                            <input type="text" class="form-control" ng-model="gst.registered_office">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State <span class="text-danger">*</span></label>
                            <select class="form-control" ng-model="gst.state_id">
                                <option ng-value=0>Select</option>
                                <option ng-repeat="state in states" ng-value="state.value">@{{state.label}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>GST No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" ng-model="gst.gst_id">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>VAT TIN </label>
                            <input type="text" class="form-control" ng-model="gst.vat_tin">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input type="text" class="form-control" ng-model="gst.contact_person">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" class="form-control" ng-model="gst.contact_name">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Default GST</label>
                            <select class="form-control" ng-model="gst.defaults" convert-to-number>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div ng-if="gst.logo" class="center-images">
                            <a href="{{url('/')}}/@{{gst.logo}}" target="_blank">
                                <img src="{{url('/')}}/@{{gst.logo}}">
                            </a>
                            <a class="btn btn-sm btn-danger btn-remove" ladda="image.processing" ng-click="removeLogo()"><i class="icons icon-close" style="color: #FFF;"></i></a>
                        </div>

                        <div ng-if="!gst.logo" style="margin-top: 20px">
                            <button class="button btn btn-primary" ngf-select="uploadGstLogo($file)" ngf-max-size="5MB" ng-disabled="logoProcessing" data-style="expand-right" >Upload Logo <span ng-show="logoProcessing" class="spinner-border spinner-border-sm"></span></button>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary mt-2" ng-hide="editForm" ng-click="submit(gst)" ng-disabled="processing">
                    Submit <span ng-if="processing" class="spinner-border spinner-border-sm"></span>
                </button>
                <button class="btn btn-primary mt-2" ng-show="editForm" ng-click="update(gst)" ng-disabled="processing">
                    Update <span ng-if="processing" class="spinner-border spinner-border-sm"></span>
                </button>
            </div>
      </div>
    </div>
</div>