<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Leads Reasons</a>
					</li>
				</ul>
			</div>
			<div class="col-md-6 text-right">
				<button class="btn btn-primary btn-sm text-right" style="margin-top: 10px" ng-click="createLeadReason()"><i class="icons icon-plus"></i> Add</button>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">
		<div class="row">
				<table class="table">
					<thead>
						<tr>
							<th>Sn</th>
							<th>Reason</th>
							<th style="width: 120px;" class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="reason in lead_reasons track by $index">
							<td>@{{$index+1}}</td>
							<td>@{{reason.reason}}</td>
							<td class="text-right">
								<button class="btn btn-light btn-sm" ng-click="editLeadReasons($index)">Edit</button>
								<button class="btn btn-danger btn-sm" ng-click="deleteLeadReasons(reason.id, $index)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
		</div>
	</div>

</div>