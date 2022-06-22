<div class="modal " id="inactive_modal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Mark Inactive</h4>
          <button type="button" class="close" ng-click="hide_data_modal('inactive_modal')">
          <i class="icons icon-close"></i></button>
        </div>

        <div class="modal-body">
          
          <div class="row">

            <div class="col-md-4  form-group">
              <label class="label-control">Reason</label>
              <select class="form-control" ng-model="inactive.reason_id">
                <option ng-value='0'>Select</option>
                <option ng-repeat="reason in inactiveReasons" ng-value="@{{reason.id}}">
                  @{{reason.reason}}
                </option>
                <option ng-value="-1">Other</option>

              </select>
            </div>

            <div class="col-md-4 form-group" ng-show="inactive.reason_id == -1">
              <label class="label-control">Other Reason</label>
              <input type="text" class="form-control" ng-model="inactive.other_reason">
            </div>

            <div class="col-md-4 form-group">
              <label class="label-control">Inactive From</label>
              <input type="text" class="datepicker form-control" 
              ng-model="inactive.inactive_from">
            </div>

            <div class="col-md-4 form-group">
              <label class="label-control">Last Class</label>
              <input type="text" class="datepicker form-control" 
              ng-model="inactive.last_class">
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" ng-disabled=" !inactive.reason_id || !inactive.inactive_from || !inactive.last_class || processing_req"
          ng-click="saveInactive(inactive)">
            @{{editModal?'Update':'Submit'}}
            <div class="spinner-border spinner-border-sm text-light" role="status" ng-if="processing_req">
              <span class="sr-only">Loading...</span>
            </div>
          </button>

          <button type="button" class="btn btn-secondary" 
           ng-click="hide_data_modal('inactive_modal')">Close</button>
        </div>

      </div>
      
    </div>
</div>

<div class="modal " id="documents_modal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Documents</h4>
          <button type="button" class="close" ng-click="hide_data_modal('documents_modal')">
          <i class="icons icon-close"></i></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 form-group">
                <label>Document Type</label><label class="required">*</label>
                <select class="form-control" ng-model="document.type_id">
                  <option ng-value=''>Select</option>
                  <option ng-repeat="type in docType" 
                  ng-value="type.id">@{{type.type}}</option>
                </select>
            </div>


            <div class="col-md-3 form-group">
                <label>Document No.</label>
                <input type="text" class="form-control" ng-model="document.document_no">
            </div>


            <div class="col-md-2 form-group">
                <label>Document</label><label class="required">*</label>
                <a href="" class="btn btn-light" ngf-select="uploadDocument($file)" 
                ng-hide="document.document_url">Select</a>

                <a href="@{{document.prev_url}}" target="_blank" ng-show="document.document_url" 
                class="btn btn-light">View</a>

                 <button class="btn btn-danger" ng-click="document.document_url=''" ng-show="document.document_url"><i class="icons icon-close"></i></button>
            </div>


            <div class="col-md-3 form-group" ng-if="document.type_id == 7">
                <label>Document Name</label><label class="required">*</label>
                <input type="text" class="form-control" ng-model="document.name">
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" ng-click="saveDocuments()" ng-disabled="processing_req">
            Submit
            <div class="spinner-border spinner-border-sm text-light" role="status" ng-if="processing_req">
              <span class="sr-only">Loading...</span>
            </div>
          </button>

          <button type="button" class="btn btn-secondary" 
           ng-click="hide_data_modal('documents_modal')">Close</button>
        </div>

      </div>
      
    </div>
</div>

<div class="modal fade" id="injury_modal" role="dialog" aria-hidden="true" style="overflow: scroll;">
    <div class="modal-dialog modal-lg">
    
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Injury Report</h4>
          <button type="button" class="close"  ng-click="hide_data_modal('injury_modal')">
          <i class="icons icon-close"></i></button>
        </div>

        <div class="modal-body">
          <div class="row">

            <div class="col-md-4 form-group">
              <label class="label-control">Injured on</label>
              <input type="text" class="datepicker form-control" ng-model="injury.injured_on">
            </div>

            <div class="col-md-4 form-group">
              <label class="label-control">Remark</label>
              <input type="text" class="form-control" ng-model="injury.remark">
            </div>

            <div class="col-md-4 form-group">
              <label class="label-control">Last Class</label>
              <input type="text" class="datepicker form-control" ng-model="injury.last_class">
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" ng-click="saveInjury(injury)" ng-disabled="processing_req">
            @{{editModal?'Update':'Submit'}}
            <div class="spinner-border spinner-border-sm text-light" role="status" ng-if="processing_req">
              <span class="sr-only">Loading...</span>
            </div>
          </button>

          <button type="button" class="btn btn-secondary" 
          ng-click="hide_data_modal('injury_modal')">Close</button>
        </div>

      </div>
      
    </div>
</div>

<div class="modal" id="group_shift_modal" role="dialog" style="overflow: scroll;">
    <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Shift Group</h4>
          <button type="button" class="close" ng-click="hide_data_modal('group_shift_modal')"><i class="icons icon-close"></i></button>
        </div>

        <div class="modal-body">
          
          <div class="row">

            <div class="col-md-4  form-group">
              <label>Effective Date</label>
              <input type="text" placeholder="Effective Date" class="datepicker form-control" ng-model="groupShifting.effective_date">
            </div>

            <div class="col-md-4  form-group">
              <label>City</label>
              <select class="form-control" ng-model="groupShifting.city_id">
                <option ng-value="">Select</option>
                <option ng-value="city.value" ng-repeat="city in state_city_center.city" >@{{city.label}}</option>
              </select>
            </div>

            <div class="col-md-4  form-group">
              <label>Center</label>
              <select class="form-control" ng-model="groupShifting.center_id">
                <option ng-value="">Select</option>
                <option ng-value="center.value" ng-repeat="center in state_city_center.center" ng-if="groupShifting.city_id == center.city_id">@{{center.label}}</option>
              </select>
            </div>

            <div class="col-md-4  form-group">
              <label>Group</label>
              <select class="form-control" ng-model="groupShifting.group_id">
                <option ng-value="">Select</option>
                <option ng-value="group.value" ng-repeat="group in state_city_center.group" ng-if="groupShifting.center_id == group.center_id">@{{group.label}}</option>
              </select>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" ng-click="groupShift()" ng-disabled="processing_req || !groupShifting.group_id">
            Submit
            <div class="spinner-border spinner-border-sm text-light" role="status" ng-if="processing_req">
              <span class="sr-only">Loading...</span>
            </div>
          </button>

          <button type="button" class="btn btn-secondary" 
           ng-click="hide_data_modal('group_shift_modal')">Close</button>
        </div>

      </div>
      
    </div>
</div>