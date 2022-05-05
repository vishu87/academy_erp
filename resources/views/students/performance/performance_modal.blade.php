<div class="modal " id="session_modal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog" role="document">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add/Update Session</h4>
          <button type="button" class="close" ng-click="hide_data_modal('session_modal')">
          &times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
              <label>Name</label>
              <input type="text" class="form-control" ng-model="session.name">
            </div>
            <div class="form-group">
              <label>Start Date</label>
              <input type="text" class="form-control datepicker" ng-model="session.start_date">
            </div>
            <div class="form-group">
              <label>End Date</label>
              <input type="text" class="form-control datepicker" ng-model="session.end_date">
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" ng-click="saveSession(session)">
            Submit <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
          </button>

          <button type="button" class="btn btn-secondary" 
           ng-click="hide_data_modal('session_modal')">Close</button>
        </div>

      </div>
      
    </div>
</div>