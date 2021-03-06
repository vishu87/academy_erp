<div class="portlet">
  <div class="portlet-head">
    <div class="table-div full">
      <div>
        <ul class="menu">
          <li class="active">
            <a href="">Attendance</a>
          </li>
        </ul>
      </div>
      <div ng-show="!loading" class="text-right">
          <a href="javascript:;" style="text-decoration: none" ng-click="prev_month()"><i class="icons icon-arrow-left-circle"></i></a>
          <a href="javascript:;" style="text-decoration:none; font-size:13px; color:#888">&nbsp;&nbsp;@{{attendance.month_name+", "+attendance.year}}&nbsp;&nbsp;</a>
          <a href="javascript:;" style="text-decoration: none" ng-click="next_month()"><i class="icons icon-arrow-right-circle"></i></a>
      </div>
    </div>
  </div>
  <div class="portlet-body">
    <table class="table-cal ng-cloak" ng-if="!loading" >
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
            <td ng-repeat="day in week" class="day-cell">
                <div ng-show="day.in_month" class="day-@{{ day.attendance }}">
                    <span class="day-meta">@{{day.date_show}}</span>
                </div>
            </td>
        </tr>
      </tbody>
    </table>

  </div>
</div>