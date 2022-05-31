@extends('layout')

<div class="" ng-controller="stock_controller" ng-init="init()">
	@section('sub_header')
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">Current Stock</h4>
				</div>
			</div>
		</div>
	@endsection

@section('content')

<div class="filters" ng-if="filter.show">
	<div class="row">

        <div class="col-sm-4 form-group">
            <label>City</label>
            <select ng-model="filter.city_id" class="form-control" required>
                <option value="">Select</option>
                <option ng-repeat="city in state_city_center.city" 
                ng-value="@{{ city.value }}">@{{city.label}}
                </option>
            </select>
        </div>

        <div class="col-sm-4 form-group">
            <label>Center</label>
            <select ng-model="filter.center_id" class="form-control" required>
                <option value="">Select</option>
                <option ng-repeat="center in state_city_center.center" ng-if="center.city_id == filter.city_id" ng-value="@{{center.value}}">@{{center.label}}
                </option>
            </select>
        </div>

        <div class="col-sm-4">
        	<button type="button" class="btn btn-primary" ng-click="searchList()" style="margin-top: 24px">Search</button>
        </div>
	</div>
</div>

		
	<div class="portlet">
		
		<div class="portlet-body ng-cloak">

			<div table-paginate></div>

			<div ng-if="loading" class="text-center mt-5 mb-5">
				<div class="spinner-grow" role="status">
				  <span class="sr-only">Loading...</span>
				</div>
			</div>

			<div class="portlet-head">
		      	<div class="row">

			        <div class="col-md-6">
			          	<ul class="menu">
				            <li class="active">
				              <a href="#">List</a>
				            </li>
			          	</ul>
			        </div>
			        <!-- <div class="col-md-6 text-right">
						<a href="{{url('inventory/request/add-request/0')}}" class="btn btn-primary"><i class="icons icon-plus"></i> Add Request</a>
			        </div> -->

		      	</div>
		    </div>
			
			<div class="table-responsive"  ng-if="!loading && dataset.length > 0">
				<table class="table">
		 			<thead>
		 				<tr>
		 					<th>SN</th>
		 					<th>City</th>
		 					<th>Center</th>
		 					<th>item</th>
		 					<th>Quantity</th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<tr ng-repeat="data in dataset track by $index">
		 					<td>@{{$index+1}}</td>
		 					<td>@{{data.city_name}}</td>
		 					<td>@{{data.center_name}}</td>
		 					<td>@{{data.item_name}}</td>
		 					<td>@{{data.quantity}}</td>
		 				</tr>
		 			</tbody>
			 	</table>
			</div>
		</div>
	</div>
</div>

@endsection

@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/stock_controller.js?v='.env('JS_VERSION')) }}" ></script>
@endsection
