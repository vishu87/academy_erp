<style type="text/css">
  .day-cell {

  }

  .day-cell.day-1 {
    background: green;
  }
  .day-cell.day-0 {
    background: red;
  }
</style>
<div class="portlet">
  <div class="portlet-head">
    <ul class="menu">
      <li class="active">
        <a href="">Attendance</a>
      </li>
    </ul>
  </div>
  <div class="portlet-body">

    <div ng-show="!loading">
        <a href="javascript:;" class="btn blue" ng-click="prev_month()"><i class="icon-chevron-left"></i>prev</a>
        <a href="javascript:;" style="text-decoration:none; font-size:13px; color:#888">&nbsp;&nbsp;@{{attendance.month_name+", "+attendance.year}}&nbsp;&nbsp;</a>
        <a href="javascript:;" class="btn blue" ng-click="next_month()"><i class="icon-chevron-right"></i>Next</a>
    </div>
    
    <table class="table table-bordered ng-cloak" ng-if="!loading" >
      <thead>
        <tr>
          <td>Sun</td>
          <td>Mon</td>
          <td>Tue</td>
          <td>Wed</td>
          <td>Thu</td>
          <td>Fri</td>
          <td>Sat</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <tr ng-repeat="week in attendance.weeks">
            <td ng-repeat="day in week" class="day-cell day-@{{ day.attendance }}">
                <div ng-show="day.in_month">
                    <span class="day-meta">@{{day.date_show}}</span>
                </div>
            </td>
        </tr>
      </tbody>
    </table>

  </div>
</div>