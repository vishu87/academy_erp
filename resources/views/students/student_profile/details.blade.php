<div class="portlet">
  <div class="portlet-head">
    <ul class="menu">
      <li class="@{{switchContent == 'payments'?'active':''}}" ng-if="student.payment_access">
        <a href="" ng-click="switchContentFun('payments')">Payments</a>
      </li>
      <li class="@{{switchContent == 'subscriptions'?'active':''}}">
        <a href="" ng-click="switchContentFun('subscriptions')">Subscriptions</a>
      </li>
      <li class="@{{switchContent == 'documents'?'active':''}}">
        <a href="" ng-click="switchContentFun('documents')">Documents</a>
      </li>
      <li class="@{{switchContent == 'inactive'?'active':''}}">
        <a href="" ng-click="switchContentFun('inactive')">Inactive</a>
      </li>
      <li class="@{{switchContent == 'injury'?'active':''}}">
        <a href="" ng-click="switchContentFun('injury')">Injury</a>
      </li>
      </li>
      <li class="@{{switchContent == 'groupShift'?'active':''}}">
        <a href=""ng-click="switchContentFun('groupShift')">Group Shift</a>
      </li>
    </ul>
  </div>
  

  <div class="portlet-body">
    <div class="mob-mt-40"></div>
    @include("students.student_profile.subscriptions")
    @include("students.student_profile.payments")
    @include("students.student_profile.documents")
    @include("students.student_profile.inactive")
    @include("students.student_profile.injury")
    @include("students.student_profile.group_shift")

  </div>
</div>

<div class="row">
  <div class="col-md-6">
    @include("students.student_profile.attendance")
  </div>
  <div class="col-md-6">
    @include("students.student_profile.performance_reports")
    @include("students.student_profile.performance_graph")
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    
  </div>
</div>