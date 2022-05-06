<x-ngform name="addForm" ng-submit="onSubmit(addForm.$valid)">

	<div class="row">
		<div class="col-md-6">
			<table class="table">
				<tbody>
					<tr>
						<td>Name</td>
						<td>@{{ formData.name }}</td>
					</tr>
					<tr>
						<td>Date Of Birth</td>
						<td>@{{ formData.name }}</td>
					</tr>
					<tr>
						<td>Father Name</td>
						<td>@{{ formData.name }}</td>
					</tr>
					<tr>
						<td>Primary Contact</td>
						<td>@{{ formData.name }}</td>
					</tr>
					<tr>
						<td>Address</td>
						<td>@{{ formData.name }}</td>
					</tr>
					<tr>
						<td>Kit Size</td>
						<td>@{{ formData.name }}</td>
					</tr>
				</tbody>
			</table>
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