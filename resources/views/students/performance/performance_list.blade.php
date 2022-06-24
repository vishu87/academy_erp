<div class="portlet">
  <div class="portlet-head">
    <div class="row">
      <div class="col-md-6">
        <ul class="menu">
          <li class="active">
            <a href="#">Students</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="portlet-body ng-cloak">
    <div ng-repeat="student in students" class="student-list" ng-click="switchStudent(student)">
      <div class="table-div full" ng-class=" student.id == studentRecord.student_id ? 'active' : '' ">
        <div>
          <a href="" ng-click="studentDetail(student.id)">@{{student.name}}</a>
        </div>
        <div class="text-right">
          <i class="icons icon-envelope" ng-if="student.mailed == 1"></i>
        </div>
      </div>
    </div>
  </div>

</div>