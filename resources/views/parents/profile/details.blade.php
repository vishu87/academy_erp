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
        <button class="btn btn-primary" ng-click="addPayment()"><i class="icons icon-plus"></i> Renew</button>
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
    @include("parents.profile.attendance")
  </div>
  <div class="col-md-6">
    @include("parents.profile.performance_reports")
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    @include("parents.profile.performance_graph")
  </div>
</div>