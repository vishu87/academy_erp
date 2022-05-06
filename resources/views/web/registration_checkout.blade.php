<x-ngform name="addForm" ng-submit="onSubmit(addForm.$valid)">

	<div class="row">
		<div class="col-md-6">
			<table class="table">
				<tbody>
					<tr>
						<td>Name</td>
						<td>@{{ reg_data.name }}</td>
					</tr>
					<tr>
						<td>Date Of Birth</td>
						<td>@{{ reg_data.dob }}</td>
					</tr>
					<tr>
						<td>Father Name</td>
						<td>@{{ reg_data.father }}</td>
					</tr>
					<tr>
						<td>Primary Contact</td>
						<td>@{{ reg_data.prim_mobile }}</td>
					</tr>
					<tr>
						<td>Address</td>
						<td>@{{ reg_data.address }}</td>
					</tr>
					<tr>
						<td>Kit Size</td>
						<td>@{{ reg_data.kit_size }}</td>
					</tr>
				</tbody>
			</table>
			<table class="table">
				<thead>
					<tr>
						<th>City</th>
						<th>Center</th>
						<th>Group</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>@{{ reg_data.trainingCity }}</td>
						<td>@{{ reg_data.center_name }}</td>
						<td>@{{ reg_data.group_name }}</td>
					</tr>
				</tbody>
			</table>
			<x-web.button type="button" ng-click="tab=1" loading="processing">Edit</x-web.button>
		</div>

		<div class="col-md-6">
			<div class="" style="background: #EEE">
				<table class="table">
					<thead>
						<tr>
							<th>Type</th>
							<th>Amount</th>
							<th>Tax</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in payment_items">
							<td>@{{ item.category }}</td>
							<td>@{{ item.amount }}</td>
							<td>@{{ item.tax_perc }}%</td>
							<td>@{{ item.total_amount }}</td>
						</tr>
					</tbody>
				</table>
			</div>

			<x-web.button type="button" class="block" loading="placing-order">Checkout</x-web.button>

		</div>
	</div>

</x-modals>