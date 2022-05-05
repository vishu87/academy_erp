<div ng-if="switchContent == 'inactive'">
  
  <div class="text-right">
    <button class="btn btn-primary" ng-click="addInactive()" ng-if="student.edit_access && student.inactive != 1">Mark Inactive</button>
  </div>

  <div class="table-responsive mt-2">
    
    <table class="table" ng-if="student.inactive_history.length > 0">
      <thead>
        <tr>
          <th>Inactive From</th>
          <th>Last class</th>
          <th>Resson</th>
          <th ng-if="student.edit_access" class="text-right"> # &nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="inactive in student.inactive_history">
          <td>@{{inactive.inactive_from}}</td>
          <td>@{{inactive.last_class}}</td>
          <td ng-hide="inactive.reason_id == -1">
            @{{inactive.reason}}
          </td>
          <td ng-show="inactive.reason_id == -1">
            @{{inactive.other_reason}}
          </td>
          <td ng-if="student.edit_access" class="text-right">
            <button class="btn-light btn btn-sm" ng-click="editInactive(inactive)">Edit</button>
            <button class="btn-danger btn btn-sm" ng-click="deleteInactive(inactive.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="alert alert-warning mt-2" ng-if="student.inactive_history.length == 0">
      No inactive history is available
    </div>

  </div>
</div>