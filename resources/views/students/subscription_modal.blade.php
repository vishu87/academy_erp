<div class="modal" id="subModal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-xl" role="document">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Subscription Details</h4>
          <button type="button" class="close" ng-click="hide_data_modal('subModal')"><i class="icons icon-close"></i></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-4 col-xs-6">
                <div class="static-info">
                  <span><i class="icons icon-calendar"></i> Start Date</span> @{{ open_subscription.start_date }}
                </div>
            </div>

            <div class="form-group col-md-4 col-xs-6">
                <div class="static-info">
                  <span><i class="icons icon-calendar"></i> End Date</span> @{{ open_subscription.end_date  }}
                </div>
            </div>
            <div class="form-group col-md-4 col-xs-6">
                <div class="static-info">
                  <span><i class="icons icon-calendar"></i> Total Adjustment</span> @{{ open_subscription.adjustment  }}
                </div>
            </div>
          </div>

          <div class="mt-3">
            <b>List of Adjustments/Pauses</b>
            <div class="table-responsive">
              <table class="table" ng-if="open_subscription.pauses.length > 0">
                <thead>
                  <tr>
                    <th>Requestor</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Added By</th>
                    <th>Requested On</th>
                    <th>Status</th>
                    <th>Approved By</th>
                    <th ng-if="student.pauses_add_access" class="text-right">#</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="pause in open_subscription.pauses">
                    <td>@{{ pause.requestor }}</td>
                    <td>@{{ pause.start_date }}</td>
                    <td>@{{ pause.end_date }}</td>
                    <td>@{{ pause.days }}</td>
                    <td>@{{ pause.added_by_name }}</td>
                    <td>@{{ pause.created_at }}</td>
                    <td>@{{ pause.status_name }}</td>
                    <td>@{{ pause.approved_by_name }}</td>
                    <td ng-if="student.pauses_add_access" class="text-right">
                      <button class="btn btn-danger btn-sm" ng-click="deletePauseRequest(pause.id)" ng-if="pause.status == 0">
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="alert alert-warning mt-2" ng-if="open_subscription.pauses.length == 0">
                No entries are available
            </div>
        </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" ng-click="hide_data_modal('subModal')">Close</button>     
        </div>

      </div>
      
    </div>
</div>

<div class="modal" id="pauseAddModal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-md" role="document">
    
      <div class="modal-content">

        <form method="POST" name="subForm" ng-submit="addPause(subForm.$valid)" novalidate="novalidate" >
          <div class="modal-header">
            <h4 class="modal-title">Subscription Pause</h4>
            <button type="button" class="close" ng-click="hide_data_modal('pauseAddModal')"><i class="icons icon-close"></i></button>
          </div>

          <div class="modal-body">
            
            <div class="row">
              <div class="form-group col-md-4 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> Start Date</span> @{{ open_subscription.start_date }}
                  </div>
              </div>

              <div class="form-group col-md-4 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> End Date</span> @{{ open_subscription.end_date  }}
                  </div>
              </div>
              <div class="form-group col-md-4 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> Total Adjustment</span> @{{ open_subscription.adjustment  }}
                  </div>
              </div>
            </div>

            <div class="mt-3">
                <div class="row">
                  <div class="col-12 form-group">
                    <label>Requested By</label>
                    <select ng-model="pauseData.requested_by" class="form-control">
                      <option value=""></option>
                      <option value="1">Academy</option>
                      <option value="2">Parent</option>
                    </select>
                  </div>
                  <div class="col-6 form-group">
                    <label>Start Date</label>
                    <input type="text" class="form-control datepicker" ng-model="pauseData.start_date" required="" />
                  </div>
                  <div class="col-6 form-group">
                    <label>End Date</label>
                    <input type="text" class="form-control datepicker" ng-model="pauseData.end_date" required="" />
                  </div>
                  <div class="col-md-12 form-group">
                    <label>Reason</label>
                    <input type="text" class="form-control" ng-model="pauseData.remarks" required="" />
                  </div>
                  <div class="col-md-3">
                    
                  </div>
                </div>
            </div>
            
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" ng-disabled="adding_pause"> Submit <span ng-show="adding_pause" class="spinner-border spinner-border-sm"></span></button>
          </div>

        </form>

      </div>
      
    </div>
</div>

<div class="modal" id="pauseApprovalModal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-md" role="document">
    
      <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title">Process Pause Request</h4>
            <button type="button" class="close" ng-click="hide_data_modal('pauseApprovalModal')"><i class="icons icon-close"></i></button>
          </div>

          <div class="modal-body">
            
            <div class="row">
              <div class="form-group col-md-4 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> Start Date</span> @{{ open_pause.start_date }}
                  </div>
              </div>

              <div class="form-group col-md-4 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> End Date</span> @{{ open_pause.end_date  }}
                  </div>
              </div>
              <div class="form-group col-md-4 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> Days</span> @{{ open_pause.days  }}
                  </div>
              </div>
            </div>

            <div class="mt-3">
                <div class="row">
                  <div class="col-md-12 form-group">
                    <label>Reason for @{{ open_pause.status == 1 ? 'Approval' : '' }} @{{ open_pause.status == 2 ? 'Rejection' : '' }}</label>
                    <input type="text" class="form-control" ng-model="open_pause.approval_remarks" required="" />
                  </div>
                  <div class="col-md-3">
                    
                  </div>
                </div>
            </div>
            
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-primary" ng-disabled="processing_req || !open_pause.approval_remarks" ng-click="processPauseRequest()"> Submit <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
          </div>

      </div>
      
    </div>
</div>