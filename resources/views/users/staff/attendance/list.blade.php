<div class="portlet">
  <div class="portlet-head">
    <div class="row">
      <div class="col-md-6">
        <ul class="menu">
          <li class="active">
            <a href="#">Staff List</a>
          </li>
        </ul>
      </div>
      <div class="col-md-6 text-right">
          <button class="btn btn-primary" ng-click="saveStaffAttendance()" ng-disabled="saveprocessing">
            Save <span ng-show="saveprocessing" class="spinner-border spinner-border-sm"></span>
          </button>
      </div>
    </div>
  </div>

  <div class="portlet-body ng-cloak">
    <div class="table-cont">
      <table class="table table-bordered">
        <thead>
          <tr>
            <td>Name</td>
            <td ng-repeat="date in dates" ng-click="addSatffAttendance(date.date)">
              @{{ date.date_show }}
            </td>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="staff in staffMembers">
            <td>@{{ staff.name }}</td>
            <td ng-repeat="date in dates" class="attendance-mark">
              <span ng-if="staff.present.indexOf(date.date) > -1" class="present" ng-click="switchStaffAttendance(staff,date.date)"></span>
              <span ng-if="staff.absent.indexOf(date.date) > -1" class="absent" ng-click="switchStaffAttendance(staff,date.date)"></span>
              <span ng-if="staff.present.indexOf(date.date) == -1 && staff.absent.indexOf(date.date) == -1" ng-click="switchStaffAttendance(staff,date.date)"></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>