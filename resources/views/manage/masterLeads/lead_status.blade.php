<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Lead Status</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">
		<div class="row">
				<table class="table">
					<thead>
						<tr>
							<th>Sn</th>
							<th>Status</th>
							<th style="width: 120px;" class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="status in lead_status track by $index">
							<td>@{{$index+1}}</td>
							<td>@{{status.status_value}}</td>
							<td class="text-right">
								<button class="btn btn-light btn-sm" ng-click="editLeadStatus($index)">Edit</button>
							</td>
						</tr>
					</tbody>
				</table>
		</div>
	</div>

</div>