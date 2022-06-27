<div class="portlet">
  <div class="portlet-head">
    <ul class="menu">
      <li class="active">
        <a href="" >Performance Reports</a>
      </li>
    </ul>
  </div>
  <div class="portlet-body">
    <div class="table-responsive" ng-if="reports.length > 0">
      <table class="table">
        <thead>
          <tr>
            <th>SN</th>
            <th>Session</th>
            <th>Created at</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="report in reports">
            <td>@{{ $index + 1 }}</td>
            <td>@{{ report.session_name }}</td>
            <td>@{{ report.created_at }}</td>
            <td class="text-right">
              <a class="btn btn-light btn-sm" href="{{url('/performance-pdf')}}/@{{report.uuid}}" target="_blank">View</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div ng-if="!loading && reports.length == 0" class="alert alert-warning">
        No reports are available
    </div>

  </div>
</div>