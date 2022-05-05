<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Groups/Batches</a>
					</li>
				</ul>
			</div>
			<div class="col-md-6 text-right">
				<button type="button" class="btn btn-success" ng-click="addGroup();">
					Add Group
				</button>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">
		<div ng-repeat="group in center.groups track by group.id">

			<div class="group-details">
				<div class="table-div full">
					<div>
						<b>@{{group.group_name}}</b>
						<span>Capacity Utilization - @{{group.active_students}} / @{{group.capacity}}</span>
					</div>
					<div class="text-right">
						<button type="button" class="btn btn-sm btn-warning" ng-click="updateGroup(group.id)">Edit</button>
					</div>
				</div>

				<div class="timings" ng-show="group.operation_timings.length > 0">

					<div ng-repeat="timing in group.operation_timings" ng-click="editTiming(timing)">
						<div class="time">
							<i class="icon-clock icons "></i> @{{timing.day_name}}, @{{timing.from_time}} to @{{timing.to_time}}
						</div>
						<div class="coaches" style="display: none;">
							<i class="icon-people icons "></i> @{{timing.coaches_list}}
						</div>
					</div>

				</div>

				<div>
					<button type="button" class="btn btn-light" ng-click="addTrainingDay(group.id)">
						<i class="icon-plus icons "></i> Add Training Day
					</button>
				</div>

			</div>

		</div>
	</div>

</div>

