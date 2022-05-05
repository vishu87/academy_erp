@extends('layout')

@section('content')

<div class="" ng-controller="payments_type_controller" ng-init="sport_id = 1; getCityCenterGroup(); getCouponList()">

	<div class="page-header row">
		<div class="col-md-6">
			<h3>Pricing Structure</h3>
		</div>
	</div>

	<div class="portlet" ng-if="filter.show">
		<div class="portlet-body">
			
		</div>
	</div>

	<div class="portlet">
		

		<div class="portlet-body ng-cloak">

			<div class="filters" style="border-bottom: 1px solid #EEE; margin-bottom: 20px;">
				<form name="filterForm" ng-submit="" novalidate>
					<div class="row" style="font-size: 14px">
						<div class="col-md-2 form-group">
							<label class="label-control">Category</label>
							<select class="form-control" ng-model="get_PTData.pay_type_cat_id">
								<option ng-value="0">Select</option>
								<option ng-repeat="cat in price_type.pay_type_cat" ng-value="cat.id">@{{cat.category_name}}</option>
							</select>
						</div>

						<div class="col-md-2 form-group">
							<label class="label-control">Sub Category</label>
							<select class="form-control" ng-model="get_PTData.pay_type_id" ng-change="getPayPriceList()">
								<option ng-value="0">Select</option>
								<option ng-repeat="type in price_type.pay_type" ng-value="type.id" ng-if="type.category_id == get_PTData.pay_type_cat_id">@{{type.name}}</option>
							</select>
						</div>
					</div>
				</form>
			</div>

			<div ng-if="!get_PTData.pay_type_id">
				<i class="icons icon-exclamation text-warning"></i> Add Please select category and type
			</div>

			<div class="row" ng-if='!processing && get_PTData.pay_type_id' >
				<div class="col-md-12 form-group text-right">
					<select class="form-control" ng-model="access_type_id" style="width: 120px; display: inline-block;">
						<option value="">Select Type</option>
						<option value="1">Default</option>
						<option value="2">For City</option>
						<option value="3">For Center</option>
						<option value="4">For Group</option>
					</select>
					<button class="btn-primary btn" ng-click="showAddPayPriceData(access_type_id,'price_access')">
						<i class="icons icon-plus"></i> Add
					</button>
				</div>
			</div>

			<div ng-if="processing" class="text-center mt-5 mb-5">
				<div class="spinner-grow" role="status">
				  <span class="sr-only">Loading...</span>
				</div>
			</div>

			<div class="table-cont" ng-if="!processing && payTypePriceList.length > 0" 
				ng-show="get_PTData.pay_type_id">
				<table class="table table-hover">
					<thead>
						<tr class="">
							<th>SN</th>
							<th>City</th>
							<th>Center</th>
							<th>Group</th>
							<th>Price</th>
							<th>Tax (%)</th>
							<th>Total</th>
							<th class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="list in payTypePriceList track by $index">
							<td>@{{$index + 1}}</td>
							<td ng-hide="list.city_id == -1">@{{list.city_name}}</td>
							<td ng-show="list.city_id == -1">Default</td>
							<td>@{{list.center_name}}</td>
							<td>@{{list.group_name}}</td>
							<td>@{{list.price}}</td>
							<td>@{{list.tax}}</td>
							<td>@{{list.total}}</td>
							<th class="text-right">
								<button class="btn btn-sm btn-light" ng-click="editPayPriceData(list)">Edit</button>
								<button class="btn btn-sm btn-danger" ng-click="deletePayPriceData(list.id)">Delete</button>
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


	@include('payments.type_price.payment_type_price_modal')

</div>
@endsection

@section('footer_scripts')
  <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/payments/pay_type_price_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection
