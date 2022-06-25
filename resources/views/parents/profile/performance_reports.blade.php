<div class="portlet">
  <div class="portlet-head">
    <ul class="menu">
      <li class="active">
        <a href="" >Performance Reports</a>
      </li>
    </ul>
  </div>
  <div class="portlet-body">
    <div class="table-responsive">
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
              <a class="btn btn-light" href="{{url('/performance-pdf')}}/@{{report.uuid}}">View</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>