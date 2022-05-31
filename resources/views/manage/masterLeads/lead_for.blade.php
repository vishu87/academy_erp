<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Leads For</a>
					</li>
				</ul>
			</div>
			<div class="col-md-6">
				<button class="btn btn-primary btn-sm text-right" style="margin-top: 10px" ng-click="createLeadFor()"><i class="icons icon-plus"></i> Add</button>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th>Sn</th>
						<th>Label</th>
						<th>Slug</th>
						<th style="width: 120px;" class="text-right">#</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="leadfor in leads_for track by $index">
						<td>@{{$index+1}}</td>
						<td>@{{leadfor.label}}</td>
						<td>@{{leadfor.slug}}</td>
						<td class="text-right">
							<button class="btn btn-light btn-sm" ng-click="editLeadFor($index)">Edit</button>
							<button class="btn btn-danger btn-sm" ng-click="deleteLeadFor(leadfor.id, $index)">Delete</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
