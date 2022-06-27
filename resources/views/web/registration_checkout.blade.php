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
			<a href="" type="button" ng-click="tab=1" loading="processing" style="text-decoration:underline;">Edit Details</a>
		</div>

		<div class="col-md-6">
			<div class="" style="background: #F2f2f2">
				<div style="padding: 15px">
					<b>@{{ reg_data.group_name }}, @{{ reg_data.center_name }}, @{{ reg_data.trainingCity }}</b>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>Item</th>
							<th>Amount</th>
							<th>Tax</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in payment_items">
							<td>@{{ item.category }}</td>
							<td>@{{ item.taxable_amount }}</td>
							<td>@{{ item.tax_perc }}%</td>
							<td>@{{ item.total_amount }}</td>
						</tr>
						<tr>
							<td colspan="4" class="text-right">
								@{{ coupon_code_message }}
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="table-div full">
				<div style="font-size: 16px;">
					Total Payable Amount: Rs. <b>@{{ total_amount }}</b>
				</div>
				<div class="text-right" ng-if="!processing_order">
					<x-web.button type="button" class="block" loading="placing-order" ng-click="createOrder()">Checkout</x-web.button>
				</div>
			</div>

			<div ng-if="processing_order">Processing your order Please Wait</div>

		</div>
	</div>

</x-modals>