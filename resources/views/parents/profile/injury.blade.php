<div ng-if="switchContent == 'injury'">
  <div class="text-right">
    <button class="btn btn-primary" ng-click="addInjury()" ng-if="student.edit_access"><i class="icons icon-plus"></i> Add Injury</button>
  </div>

  <div class="table-cont mt-2" ng-if="student.injuries.length > 0">
      <table class="table">
        <thead>
          <tr>
            <th>SN</th>
            <th>Injured on</th>
            <th>Last Class</th>
            <th>Remarks</th>
            <th ng-if="student.edit_access" class="text-right">#</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="injury in student.injuries track by $index">
            <td>@{{$index+1}}</td>
            <td>@{{injury.injured_on}}</td>
            <td>@{{injury.last_class}}</td>
            <td>@{{injury.remark}}</td>
            <td ng-if="student.edit_access" class="text-right">
              <button class="btn-light btn btn-sm" ng-click="editInjury(injury)">Edit</button>
              <button class="btn-danger btn btn-sm" ng-click="deleteInjury(injury.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  </div>

  <div class="alert alert-warning mt-2" ng-if="student.injuries.length == 0">
    No injury history is available
  </div>

</div>