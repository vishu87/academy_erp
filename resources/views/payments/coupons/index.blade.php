@extends('layout')

@section('content')

<div class="" ng-controller="coupon_controller" ng-init="sport_id = 1; init(); getCouponList();">

	<div class="page-header row">
		<div class="col-md-6">
			<h3>Coupons</h3>
		</div>
	</div>

	<div class="portlet">
		<div class="portlet-body ng-cloak">

 			<div class="row" ng-if='!processing'>
				<div class="col-md-12 form-group text-right">
					<button class="btn-primary btn" ng-click="showCouponModal()">
						<i class="icons icon-plus"></i> Add Coupon
					</button>
				</div>
			</div> 

			<div ng-if="processing" class="text-center mt-5 mb-5">
				<div class="spinner-grow" role="status">
				  <span class="sr-only">Loading...</span>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table" ng-if='!processing'>
					<thead>
						<tr class="">
							<th>SN</th>
							<th>Code</th>
							<th>Discount Type</th>
							<th>Discount</th>
							<th>Category</th>
							<th>Sub Category</th>
							<th>Expiry Date</th>
							<th>#</th>
							<th class="text-right" style="width: 300px;">Available For</th>
						</tr>
					</thead>
					<tbody ng-repeat="coupon in couponData track by $index">
						<tr>
							<td>@{{$index + 1}}</td>
							<td class="theme-color">
								<b>@{{coupon.code}}</b>
							</td>
							<td ng-if="coupon.discount_type == 1">Percentage (%)</td>
							<td ng-if="coupon.discount_type == 2">Amount</td>
							<td>@{{coupon.discount}}</td>
							<td>@{{coupon.catName}}</td>
							<td>@{{coupon.subCatName}}</td>
							<td>@{{coupon.expiry_date}}</td>
							<td>
								<button class="btn btn-sm btn-light" ng-click="editCoupon(coupon)">Edit</button>
								<button class="btn btn-sm btn-danger" ng-click="deleteCoupon(coupon, $index)">Delete</button>
							</td>
							<td class="text-right">
								<select ng-model="coupon.type" class="form-control" style="width: 150px; display: inline-block;">
									<option value="">Select</option>
									<option value="1">All Cities</option>
									<option value="2">City</option>
									<option value="3">Center</option>
									<option value="4">Group</option>
								</select>
								<button type="button" class="btn btn-primary" ng-click="show_location_access_model(coupon)">
									Add
								</button>
							</td>
						</tr>
						<tr class="no-top-border" ng-if="coupon.locations.length > 0">
							<td colspan="9">
								<div ng-repeat="loc in coupon.locations" class="location-tag">
									@{{ loc.city_id == -1 ? 'All Cities' : '' }}
									@{{ loc.city_id > 0 && !loc.center_id ? loc.city_name : '' }}
									@{{ loc.center_id > 0 ? loc.center_name : '' }}
									@{{ loc.group_id > 0 ? loc.group_name : '' }}
									<span class="delete" ng-click="delete_coupon_mapping(loc)" 
									aria-hidden="true">&times;</span>
								</div>
							</td>
						</tr>
					</tbody>			
				</table>
			</div>

			<div class="table-cont" ng-if="!processing && payTypePriceList.length == 0">
				<div class="alert alert-warning">
					No data is available. Kindly add at-least a default payment plan to activate the category
				</div>
			</div>

		</div>
	</div>


	@include('payments.coupons.modals')

</div>
@endsection
