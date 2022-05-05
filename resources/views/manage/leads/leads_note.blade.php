<div class="table-div full">
    <div class="">
        <h4 style="display: inline-block;">Add Note</h4>
    </div>
    <div>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
    </div>
</div>
<div>
    <form name="noteForm" novalidate ng-submit="addNoteSubmit(noteForm.$valid)" > 
        
        <div class="row">
            
            <div class="col-md-4 form-group">
                <label>Lead Status <span class="error">*</span></label>
                <select class="form-control" ng-change="checkReason(noteData.status)" convert-to-number ng-model="noteData.status" required>
                    <option>Select</option>
                    <option ng-repeat="st in parameters.status" value="@{{st.value}}">@{{st.label}}</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label>Assigned To <span class="required">*</span></label>
                <select class="form-control" ng-model="noteData.assigned_to" required>
                    <option>Select</option>
                    <option ng-repeat="member in parameters.members" ng-value="member.value">@{{member.label}}</option>
                </select>
            </div>

            <div class="col-md-4 form-group" ng-if="status_row.date_req == 1">
                <label>@{{ status_row.action_date_name }} <span class="required">*</span></label>
                <input type="text" ng-model="noteData.action_date" class="form-control datepicker" >
            </div>
            

            <div class="col-md-4 form-group" ng-if="status_row.reason_req == 1">
                <label>Reason <span class="required">*</span></label>
                <select class="form-control" ng-model="noteData.reason_id" convert-to-number>
                    <option ng-repeat="reason in parameters.reasons" value="@{{reason.id}}">@{{reason.reason}}</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label>Call made</label><br>
                <label><input type="radio" ng-model="noteData.call_made" ng-value="1"> Yes</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" ng-model="noteData.call_made" ng-value="0"> No</label>
            </div> 

            <div class="col-md-12 form-group" >
                <label>Call Note <span class="required" ng-if="status_row.call_note_req == 1">*</span></label>
                <textarea ng-model="noteData.call_note" class="form-control" ng-required="status_row.call_note_req == 1"></textarea>
            </div>

            <div class="col-md-12">
                <button class="btn btn-primary" type="submit" ng-disabled="adding_note">Add Note <span ng-show="adding_note" class="spinner-border spinner-border-sm"></button>
            </div>
        </div>
    </form>
</div>
<div ng-show="leadData.history.length > 0">
    <hr>
    <b>Lead History</b>
    <div class="table-responsive" style="height: 300px;overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th style="min-width: 120px">Status</th>
                    <th style="min-width: 80px">Action Date</th>
                    <th>Assigned To</th>
                    <th>Call Note</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="record in leadData.history">
                    <td>@{{record.status_value}} <small style="display: block;">@{{record.created_at|date}}</small><small style="display: block;">@{{record.assigned_by}}</small></td>
                    <td>@{{record.action_date|date}}</td>
                    <td>@{{record.assigned_member}}</td>
                    <td><small>@{{record.call_note}}</small></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>