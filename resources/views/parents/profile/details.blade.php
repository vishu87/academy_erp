<div class="portlet">
  <div class="portlet-head">
    <div class="table-div full">
      <div class="">
        <ul class="menu">
          <li class="@{{switchContent == 'payments'?'active':''}}">
            <a href="" ng-click="switchContentFun('payments')">Payments</a>
          </li>
          <li class="@{{switchContent == 'subscriptions'?'active':''}}">
            <a href="" ng-click="switchContentFun('subscriptions')">Subscriptions</a>
          </li>
        </ul>
      </div>

      <div class="text-right">
        <a href="{{url('/renewals')}}" class="btn btn-primary" target="_blank"><i class="icons icon-plus"></i> Renew</a>
      </div>
    </div>
  </div>
  <div class="portlet-body">
    @include("parents.profile.subscriptions")
    @include("parents.profile.payments")
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    @include("students.student_profile.attendance")
  </div>
  <div class="col-md-6">
    @include("students.student_profile.performance_reports")
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    @include("students.student_profile.performance_graph")
  </div>
</div>