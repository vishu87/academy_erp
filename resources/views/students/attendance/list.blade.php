<div class="portlet">
  <div class="portlet-head">
    <div class="row">
      <div class="col-md-6 col-8">
        <ul class="menu">
          <li class="active">
            <a href="#">List of Students</a>
          </li>
        </ul>
      </div>
      <div class="col-md-6 col-4 text-right">
          <button class="btn btn-primary" ng-click="saveAttendance()" ng-disabled="saveprocessing">
            Save <span ng-show="saveprocessing" class="spinner-border spinner-border-sm"></span>
          </button>
      </div>
    </div>
  </div>

  <div class="portlet-body ng-cloak">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th >
              <div style="width: 200px;">Students</div>
            </th>
            <th ng-repeat="date in dates" ng-click="addAttendance(date.date)">
            @{{ date.date_show.substr(0,2) }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="student in students">
            <td><a href="" ng-click="studentDetail(student.id)">@{{student.name}}</a></td>
            <td ng-repeat="date in dates" class="attendance-mark">
              <span ng-if="student.present.indexOf(date.date) > -1" class="present" ng-click="switchAttendance(student,date.date)"></span>
              <span ng-if="student.absent.indexOf(date.date) > -1" class="absent" ng-click="switchAttendance(student,date.date)"></span>
              <span ng-if="student.present.indexOf(date.date) == -1 && student.absent.indexOf(date.date) == -1" ng-click="switchAttendance(student,date.date)"></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>