<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6 col-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Leads Sources</a>
					</li>
				</ul>
			</div>
			<div class="col-md-6 col-6 text-right">
				<button class="btn btn-primary btn-sm text-right" style="margin-top: 10px" ng-click="createLeadSources()"><i class="icons icon-plus"></i> Add</button>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">
		<div class="row">
				<table class="table">
					<thead>
						<tr>
							<th>Sn</th>
							<th>Source</th>
							<th style="width: 120px;" class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="sources in lead_sources track by $index">
							<td>@{{$index+1}}</td>
							<td>@{{sources.source}}</td>
							<td class="text-right">
								<button class="btn btn-light btn-sm" ng-click="editLeadSources($index)">Edit</button>
								<button class="btn btn-danger btn-sm" ng-click="deleteLeadSources(sources.id, $index)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
		</div>
	</div>

</div>