<div class="row">
  <div class="col-md-6" ng-if="student.mobile" ng-click="showInfo(student.mobile,'mobile')" style="cursor: pointer;">
    <i class="icons icon-phone"></i> @{{ student.mobile }} &nbsp;&nbsp;
  </div>
  <div class="col-md-6" ng-if="student.email" ng-click="showInfo(student.email,'email')" style="cursor: pointer;">
    <i class="icons icon-envelope-open"></i> @{{ trimText(student.email,10) }}
  </div>
</div>