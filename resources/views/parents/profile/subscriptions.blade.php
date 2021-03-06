<div ng-if="switchContent == 'subscriptions'">

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>SN</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Month Plan</th>
            <th>Adjustment (Days)</th>
            <th class="text-right" ng-if="student.pauses_add_access">#</th>
          </tr>
        </thead>
        <tbody ng-repeat="sub in student.subscriptions">
          <tr>
            <td>
              <a href="" ng-click="viewSubscription(sub.id)">@{{ sub.code }}</a>
            </td>
            <td>@{{ sub.start_date }}</td>
            <td>@{{ sub.end_date }}</td>
            <td>@{{ sub.months }}</td>
            <td>@{{ sub.adjustment }}</td>
            <td class="text-right" ng-if="student.pauses_add_access">
              <button class="btn btn-light btn-sm" ng-click="editSubscription(sub)" ng-if="$index == 0">
                Request Pause
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <hr>

    <div class="table-div full" ng-if="student.pauses.length > 0">
      <div>
        <b>Pause Requests under Approval</b>
      </div>
    </div>
    <div class="table-responsive" ng-if="student.pauses.length > 0">
      <table class="table" >
        <thead>
          <tr>
            <th>Requestor</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Days</th>
            <th>Added By</th>
            <th>Requested On</th>
            <th ng-if="student.pauses_approve_access" class="text-right">#</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="pause in student.pauses">
            <td>@{{ pause.requestor }}</td>
            <td>@{{ pause.start_date }}</td>
            <td>@{{ pause.end_date }}</td>
            <td>@{{ pause.days }}</td>
            <td>@{{ pause.added_by_name }}</td>
            <td>@{{ pause.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>
</div>