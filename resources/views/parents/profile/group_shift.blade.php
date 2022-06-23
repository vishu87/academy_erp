<div ng-if="switchContent == 'groupShift'">
  <div class="text-right">
    <button class="btn btn-primary" ng-click="addGroupShift()" ng-if="student.edit_access"><i class="icons icon-plus"></i> Add New</button>
  </div>
  <div class="table-cont mt-2" ng-if="student.group_shifts.length > 0">
      <table class="table">
        <thead>
          <tr>
            <th>Effective Date</th>
            <th>Old Center</th>
            <th>Old Group</th>
            <th>New Center</th>
            <th>New Group</th>
            <th>Added By</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="shift in student.group_shifts">
            <td>@{{shift.effective_date}}</td>
            <td>@{{shift.old_center_name}}, @{{shift.old_city_name}}</td>
            <td>@{{shift.old_group_name}}</td>
            <td>@{{shift.center_name}}, @{{shift.city_name}}</td>
            <td>@{{shift.group_name}}</td>
            <td>@{{shift.user_name}}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="alert alert-warning mt-2" ng-if="student.group_shifts.length == 0">
      No group shift history is available
    </div>

</div>