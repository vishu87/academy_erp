@extends('layout_web')

@section('content')

@if($payment_gateway == "razorpay")
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script type="text/javascript">
		payment_code = "{{$payment_code}}";
	</script>
@endif

<x-web.container :background="$background" :logo="$logo_url" controller="RenewalCtrl" init="">

	<div ng-show="!show_success">
		<div>
			<div class="steps">
				
				<div class="step">
					<div class="head" ng-class=" step == 1 ? 'active' : '' ">
						<div class="table-div full">
							<div class="number1">
								<div>1</div>
							</div>
							<div>
								<div class="name">
									Mobile Number
									<span class="icon has-text-success" ng-if="step > 1">
									  <i class="icons icon-check"></i>
									</span>
								</div>
								<div class="selected-value" ng-if="step > 1">@{{filter.mobile_number}}</div>
							</div>
							<div style="text-align: right;" ng-if="step > 1">
								<button type="button" ng-click="clickStep(1)" class="btn btn-sm btn-light">Edit</button>
							</div>
						</div>
					</div>
					<div class="body" ng-if="step == 1">
						<div class="table-div top">
							<div style="width:300px">
								<div class="form-group">
									<input type="text" class="form-control" ng-model="filter.mobile_number" placeholder="Enter your mobile number">
								</div>
							</div>
							<div style="padding-left: 20px;">
								<button type="button" ng-click="submitStep1()" class="btn btn-light" ng-disabled="processing">
									Continue
									<span ng-if="processing" class="spinner-border spinner-border-sm"></span>
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="step">
					<div class="head" ng-class=" step == 2 ? 'active' : '' ">
						<div class="table-div full">
							<div class="number1">
								<div>2</div>
							</div>
							<div>
								<div class="name">
									Select Student
									<span class="icon has-text-success" ng-if="step > 2">
									  <i class="icons icon-check"></i>
									</span>
								</div>
								<div class="selected-value" ng-if="step > 2">@{{student.code}} @{{student.name}}</div>
							</div>
							<div style="text-align: right;" ng-if="step > 2">
								<button type="button" ng-click="clickStep(2)" class="btn btn-sm btn-light">Edit</button>
							</div>
						</div>
					</div>
					<div class="body" ng-if="step == 2">
						<div class="">
							<table class="table" style="width: 100%">
								<thead>
									<tr>
										<th>Name</th>
										<th>Group</th>
										<th>Renewal Due On</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="student in students">
										<td>
											@{{ student.name }}<br>
											<span style="font-size: 12px;">@{{ student.code }} DOB: @{{ student.dob }}</span>
										</td>
										<td>@{{ student.group_name }}, @{{ student.center_name }}</td>
										<td>@{{ student.doe }}</td>
										<td>
											<button type="button" ng-click="selectStudent(student)" class="btn btn-light">Select</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="step">
					<div class="head" ng-class=" step == 3 ? 'active' : '' ">
						<div class="table-div">
							<div class="number1">
								<div>3</div>
							</div>
							<div>
								<div class="name">
									Select Subscription Plan
									<span class="icon has-text-success" ng-if="step > 3">
									  <i class="icons icon-check"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="body" ng-if="step == 3">
						<div>
							<div ng-if="processing">
								<progress class="progress is-small is-primary" max="100"></progress>
							</div>	
							<div ng-if="!processing">

								<div class="row" ng-if="student.group_id">
									<div class="col-md-6" ng-repeat="item in payment_options">
										<div >
											<div class="form-group">
											    <label>@{{item.label}} <span ng-if="item.required" class="text-danger">*</span></label>
											    <select class="form-control" ng-model="item.type_id" ng-change="getPaymentItems()" ng-required="item.required">
											        <option value="">Select</option>
													<option ng-repeat="type in item.types" value="@{{type.value}}" >@{{type.label}}</option>
											    </select>
											</div>
										</div>
									</div>
								</div>

								<div ng-if="student.group_id">
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
												<tr ng-repeat="item in payment_items">
													<td>@{{ item.category }}</td>
													<td>
														@{{ item.taxable_amount }}
														<span ng-if="item.discount">Saved Rs. @{{ item.discount }}</span>
													</td>
													<td>@{{ item.tax_perc }}%</td>
													<td>@{{ item.total_amount }}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

								<div class="table-div full">
									<div >
										<div ng-if="!coupon_code" >
											<div class="table-div apply-div" >
												<div class="input">
													<input type="text" ng-model="formData.coupon_code" class="form-control" placeholder="Discount code" />
												</div>
												<div class="pl-3">
													<button type="button" ng-click="checkCoupon()" class="btn btn-sm btn-info">Apply</button>
													
												</div>
											</div>
										</div>
										<div ng-if="coupon_code">
											<p class="mt-2 mb-1">
												Coupon applied 
											</p>
											<div class="coupon-code-box table-div">
												<span class="d-inline-block coupon">
													@{{ coupon_code }}
												</span>
												<button type="button" ng-click="removeCoupon()" class="remove-coupon">
													<i class="icon-close"></i>
												</button>
											</div>
											<p>
												
												<small>@{{ coupon_code_message }} is the demo  YOur code is added</small>
											</p>
											
										</div>
										<div>

				
											
										</div>
									</div>
									<div class="text-center" style="font-size: 16px; width: 200px">
										Total Amount: <b>@{{ total_amount }} 2089</b>
									</div>
								</div>

								<div class="pt-2 text-right">
									<x-web.button type="button" class="block" loading="placing-order" ng-click="createOrder()">Checkout</x-web.button>
								</div>

							</div>	
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>

	<div class="columns" ng-show="show_success">
		<div class="">
			<div class="step">
				<div class="body" style="text-align: center; padding: 50px;">
					<div class="" >
						<img src="checked.png" style="width: 120px; height: 120px;" />
						<h4 style="font-size: 20px;">Thank you @{{student.name}} for renewing your subscription. We are excited to have you back!</h4>
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
						<p style="font-size: 14px; margin-bottom: 15px;">
							With this subscription, you also get unlimited access to the enJogo mobile app which has loads of content curated by our in-house experts especially for you. With enJogo, you can now continue to improve both on the field and at home!
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

</x-web.container>

@endsection