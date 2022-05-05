<div class="access_rights">
	<div class="access-right" ng-repeat="right in user_access_rights">
		<div class="row">
			<div class="col-6">
				<div class="name">
					@{{right.access_rights}}
				</div>
			</div>
			<div class="col-6 text-right">
				<select ng-model="rights.access_rights_add_id" class="form-control" style="width: 110px; display: inline-block;">
					<option value=""> Select</option>
					<option ng-repeat="item in access_right_type" value="@{{item.id}}" ng-if="item.id <= right.geography_limit">
						<button type="button">
							@{{item.type}}
						</button>
					</option>
				</select>
				<button type="button" class="btn btn-primary" ng-click="show_location_access_model(rights.access_rights_add_id, right)">
					Add Access
				</button>
			</div>
			<div class="col-md-12">
				<div>
					<span ng-repeat="loc in right.locations" 
					ng-if="loc.city_id == -1">
						<button type="button" class="location-tag">
							All Access
							<span ng-click="delete_access_location_data(loc)" 
							aria-hidden="true">&times;</span>
						</button>
					</span>	

					<span ng-repeat="loc in right.locations"
					ng-if="loc.city_name != null" ng-hide="loc.center_name">
						<button type="button" class="location-tag">
							@{{loc.city_name}}
							<span ng-click="delete_access_location_data(loc)" 
							aria-hidden="true">&times;</span>
						</button>
					</span>

					<span ng-repeat="loc in right.locations"
					ng-if="loc.center_name != null" ng-hide="loc.group_name">
						<button type="button" class="location-tag">
							@{{loc.center_name}}
							<span ng-click="delete_access_location_data(loc)" 
							aria-hidden="true">&times;</span>
						</button>
					</span>

					<span ng-repeat="loc in right.locations"
					ng-if="loc.group_name != null">
						<button type="button" class="location-tag">
							@{{loc.group_name}} -, @{{loc.center_name}}
							<span ng-click="delete_access_location_data(loc)" 
							aria-hidden="true">&times;</span>
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="text-right">
			<button type="button" class="btn" ng-if="$index == 0 && right.locations.length > 0" ng-disabled="processing_req" ng-click="copyToAll(right.id)">Copy to all <span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
		</div>
	</div>
</div>