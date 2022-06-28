@extends('layout_web')

@section('content')

@if($payment_gateway == "razorpay")
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script type="text/javascript">
		payment_code = "{{$payment_code}}";
	</script>
@endif

<x-web.container :background="$background" :logo="$logo_url" controller="ClientPaymentCtrl" :footer="$params->param_37">

	<div ng-show="!show_success && !loading">
		<div ng-show="!invalid">
			<div class="step">
				<table class="table">
					<tr>
						<td>Name</td>
						<td>@{{ student.name }}</td>
					</tr>
					<tr>
						<td>DOB</td>
						<td>@{{ student.dob }}</td>
					</tr>
					<tr>
						<td>Group/Center</td>
						<td>@{{ student.group_name }}, @{{ student.center_name }}</td>
					</tr>
				</table>	
			</div>
			<b>Payment Information</b>
			<div>
				<div class="" style="background: #F2F2F2">
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
							<tr ng-repeat="item in payment.items">
								<td>@{{ item.category }}</td>
								<td>
									₹ @{{ item.taxable_amount | INR}}
									<span ng-if="item.discount" class="save-tag green">Saved ₹ @{{ item.discount  | INR}}</span>
								</td>
								<td>@{{ item.tax_perc }}%</td>
								<td>₹ @{{ item.total_amount | INR}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="table-div full">
				<div class="" style="font-size: 16px; width: 200px">
					Total Amount: <b>₹ @{{ total_amount  | INR}}</b>
				</div>
				<div class="text-right">
					<x-web.button type="button" class="block" loading="placing-order" ng-click="createOrder()">Checkout</x-web.button>
				</div>
			</div>
		</div>

		<div ng-show="invalid">
			<h3 style="text-align: center;">Invalid link</h3>
		</div>
	</div>

	<div ng-show="show_success">
		<div class="">
			<div class="step">
				<div class="body" style="text-align: center; padding: 50px;">
					<div class="" >
						<img src="{{ url('assets/images/checked.png') }}" style="width: 120px; height: 120px;" />
						<h4 style="font-size: 20px; margin-top: 20px">Thank you @{{student.name}} for the payment. We are excited to see you on the field!</h4>
						<table class="table" style="width: 100%; margin-top: 20px;">
							<tr>
								<td>Date & Time</td>
								<td>@{{ datetime }}</td>
							</tr>
							<tr>
								<td>Amount</td>
								<td>₹ @{{ total_amount }}</td>
							</tr>
							<tr>
								<td>Order ID</td>
								<td>@{{ order_id }}</td>
							</tr>
							<tr>
								<td>Transaction ID</td>
								<td>@{{ transaction_id }}</td>
							</tr>
						</table>
						<div style="margin-bottom: 15px; font-size: 14px">
							{!! $params->param_36 !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-web.container>


@endsection