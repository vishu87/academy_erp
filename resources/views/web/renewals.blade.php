@extends('layout_web')

@section('content')
	
	<div class="container ng-cloak" id="payments" style="padding-top: 100px" ng-app="app" ng-controller="RenewalCtrl">
		<div class="columns" ng-show="!show_success">
			<div class="column is-8 is-offset-2">
				<div class="steps">
					
					<div class="step">
						<div class="head" ng-class=" step == 1 ? 'active' : '' ">
							<div class="table-div">
								<div class="number1">
									<div>1</div>
								</div>
								<div>
									<div class="name">
										Mobile Number
										<span class="icon has-text-success" ng-if="step > 1">
										  <i class="fa fa-check-square"></i>
										</span>
									</div>
									<div class="selected-value" ng-if="step > 1">@{{filter.mobile_number}}</div>
								</div>
								<div style="text-align: right;" ng-if="step > 1">
									<button type="button" ng-click="clickStep(1)" class="button is-small">Edit</button>
								</div>
							</div>
						</div>
						<div class="body" ng-if="step == 1">
							<div class="">
								<div class="columns">
									<div class="column">
										<label class="label">Mobile Number</label>
										<input class="input" type="text" ng-model="filter.mobile_number">
									</div>
								</div>
								<div>
									<button type="button" ng-click="submitStep1()" class="button is-primary" ng-class="step == 1 && processing ? 'is-loading' : '' ">Continue</button>
								</div>
							</div>
						</div>
					</div>

					<div class="step">
						<div class="head" ng-class=" step == 2 ? 'active' : '' ">
							<div class="table-div">
								<div class="number1">
									<div>2</div>
								</div>
								<div>
									<div class="name">
										Select Student
										<span class="icon has-text-success" ng-if="step > 2">
										  <i class="fa fa-check-square"></i>
										</span>
									</div>
									<div class="selected-value" ng-if="step > 2">@{{student.code}} @{{student.name}}</div>
								</div>
								<div style="text-align: right;" ng-if="step > 2">
									<button type="button" ng-click="clickStep(2)" class="button is-small">Edit</button>
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
												<button type="button" ng-click="selectStudent(student)" class="button is-small">Select</button>
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
										  <i class="fa fa-check-square"></i>
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
									<div class="notification is-warning mb-0" ng-if="subs.length == 0">
									  @{{ message }}
									</div>

									<div ng-if="subs.length > 0">
									  	<div style="font-size: 14px; font-weight: bold;margin-bottom: 10px;">
									  		Product Name: Non-Residential | Football | On-Field Training
									  	</div>
									  	<div class="columns">
											<div class="column">
												<div ng-repeat="sub in subs" class="sub" ng-class="$index == sub_index ? 'active' : '' " ng-click="selectSub(sub,$index)">
													<b>@{{ sub.months }}</b> <br>
													INR @{{ sub.amount }}
												</div>
											</div>

											<div class="column">
												<table class="table" style="width: 100%">
													<tr ng-repeat="row in sub.rows" ng-hide="sub.discount == 0 && ($index == 1 || $index == 2) ">
														<td>@{{row.name}}</td>
														<td style="width: 50%; text-align: right;">@{{row.amount}}</td>
													</tr>
												</table>
												<div style="text-align: center;" ng-if="!processing_order">
													<button type="button" ng-if="sub_index != -1" ng-click="createOrder()" class="button is-primary" ng-class=" placing_order ? 'is-loading' : '' ">Checkout</button>
												</div>
												<progress class="progress is-small is-primary" max="100" ng-if="processing_order" ></progress>
											</div>

										</div>

									</div>

								</div>	
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>

		<div class="columns" ng-show="show_success">
			<div class="column is-6 is-offset-3">
				<div class="step">
					<div class="body" style="text-align: center; padding: 50px;">
						<div class="" >
							<img src="checked.png" style="width: 120px; height: 120px;" />
							<h4 style="font-size: 20px;">Thank you @{{student.name}} for renewing your subscription with BBFS by enJogo. We are excited to have you back!</h4>
							<table class="table" style="width: 100%; margin-top: 20px;">
								<tr>
									<td>Product Name</td>
									<td>Product Name: Non-Residential | Football | On-Field Training</td>
								</tr>
								<tr>
									<td>Plan Name</td>
									<td>@{{ sub.months }}</td>
								</tr>
								<tr>
									<td>Date & Time</td>
									<td>@{{ datetime }}</td>
								</tr>
								<tr>
									<td>Amount</td>
									<td>₹ @{{ sub.total_amount }}</td>
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
							<p style="font-size: 14px; margin-bottom: 15px;">
								enJogo is available on the <a href="https://play.google.com/store/apps/details?id=com.bbfs.parent&hl=en" target="_blank">Play Store</a> as well as the <a href="https://apps.apple.com/in/app/talisman-bbfs/id1484241127" target="_blank">App Store</a>.
							</p>
							<p style="font-size: 14px; margin-bottom: 15px;">
								Happy football!!
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection