<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Center Details</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>City <span class="error">*</span></label>
					<select class="form-control" ng-model="center.city_id">
						<option ng-value="">Select</option>
						<option ng-repeat="city in cities" ng-value="city.id">@{{city.city_name}}</option>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Center Name <span class="error">*</span></label>
					<input type="text" class="form-control" ng-model="center.center_name">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Center Start Date</label>
					<input type="text" class="form-control datepicker" ng-model="center.center_dos">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Address</label>
					<input type="text" class="form-control" ng-model="center.address">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Short Url</label>
					<input type="text" class="form-control" ng-model="center.short_url">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Center Capacity</label>
					<input type="text" class="form-control" ng-model="center.center_capacity">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Status</label>
					<select class="form-control" ng-model="center.center_status" convert-to-number>
						<option value="">Select</option>
						<option value="0">Active</option>
						<option value="1">Inactive</option>
					</select>
				</div>
			</div>
			<div class="col-md-3 form-group">
				
				<label>Coordinator</label>
				<select class="form-control" ng-model="center.cordinator_id" convert-to-number>
					<option value="">Select</option>
					<option value="@{{member.id}}" ng-repeat="member in cordinators">@{{member.name}}</option>
				</select>
			</div>

			<div class="col-md-3 form-group">
				<label>Ground Size(mt)</label>
				<div class="row">
					<div class="col-md-5">
						<input type="text" placeholder="Length" class="form-control" ng-model="center.ground_length">
					</div>
					<div class="col-md-1"><div style="margin-top: 10px">X</div></div>
					<div class="col-md-5">
						<input type="text" placeholder="Width" class="form-control" ng-model="center.ground_width">
					</div>
				</div>
			</div>

			<div class="col-md-3 form-group">
				<label>Hide On App/Website</label><br>
				<label>
					<input type="radio" ng-model="center.hide_on_app" ng-value="1"> &nbsp;Yes
				</label>
				&nbsp;&nbsp;&nbsp;
				<label>
					<input type="radio" ng-model="center.hide_on_app" ng-value="0">&nbsp; No
				</label>
			</div>

			<div class="col-md-12" style="margin-top: 20px;">
				<button class="btn btn-primary" ladda="processing">@{{update==true ? 'Update':'Add'}}</button>
			</div>

		</div>
	</div>

</div>