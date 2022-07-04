@extends('layout')

	@section('sub_header')
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">Inventory Request</h4>
				</div>
				<div class="text-right">
				</div>
			</div>
		</div>
	@endsection

@section('content')
<div class="" ng-controller="Request_controller" ng-init="init()">		
	<div class="portlet">

	    <div class="portlet-head">
	      	<div class="row">
		        <div class="col-md-6 col-6">
		          	<ul class="menu">
			            <li class="active">
			              <a href="#">List</a>
			            </li>
		          	</ul>
		        </div>
		        <div class="col-md-6 col-6 text-right">
					<a href="{{url('inventory/request/add-request')}}" class="btn btn-primary"><i class="icons icon-plus"></i> Add Request</a>
		        </div>
	      	</div>
	    </div>

		<div class="portlet-body ng-cloak">
		    <div table-paginate></div>  
			<div class="filters" ng-if="filter.show">

				<form name="filterForm" ng-submit="" novalidate>
				
					<div class="row">

						<div class="col-md-3 form-group ">
			                <label>Date</label>
			                <input type="text" ng-model="filter.date" class="form-control datepicker">
			            </div>

						<div class="col-md-3 form-group">
			                <label>Type</label>
			                <select class="form-control" ng-model="filter.type">
                                <option>Select Type</option>
                                <option value="1">Purchase</option>
                                <option value="2">Transfer</option>
                                <option value="3">Consume</option>
                            </select>
			            </div>

			            <div class="col-md-12 mb-5">
			            	<button  ng-click="searchList()" class="btn btn-primary" ng-disabled="loading">
			            		Apply
			            		<div class="spinner-border spinner-border-sm text-light" role="status" ng-if="loading">
								  <span class="sr-only">Loading...</span>
								</div>
			            	</button>
			            </div>
					</div>
				</form>

			</div>


			<div ng-if="loading" class="text-center mt-5 mb-5">
				<div class="spinner-grow" role="status">
				  <span class="sr-only">Loading...</span>
				</div>
			</div>	
			<div class="table-responsive" ng-if="!loading && dataset.length > 0">
				<table class="table">
		 			<thead>
		 				<tr>
		 					<th>SN</th>
		 					<th>Date</th>
		 					<th>Type</th>
		 					<th>Company Name</th>
		 					<th>Invoice Number</th>
		 					<th>Remark</th>
		 					<th>Status</th>
		 					<th class="text-right">#</th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<tr ng-repeat="data in dataset track by $index">
		 					<td>@{{$index+1}}</td>
		 					<td>@{{ data.date | date }}</td>
		 					<td ng-if="data.type == 1">Purchase</td>
		 					<td ng-if="data.type == 2">Transfer</td>
		 					<td ng-if="data.type == 3">Consume</td>
		 					<td>@{{data.companyName}}</td>
		 					<td>@{{data.invoice_number}}</td>
		 					<td>@{{data.status_name}}</td>
		 					<td class="text-right">
            					<button type="button" class="btn btn-sm btn-light" ng-click="viewInventoryRequest(data.id)">View</button>
								<a href="{{url('inventory/request/add-request/')}}/@{{data.id}}" class="btn btn-sm btn-light" ng-if="data.status == 0">Edit</a>
            					<button type="button" class="btn btn-sm btn-danger" ng-click="deleteRequest(data.id, $index)" ng-if="data.status == 0">Delete</button>
		 					</td>
		 				</tr>
		 			</tbody>
			 	</table>
			</div>
		</div>
	</div>
	@include('manage.request.request_view_modal')
</div>

@endsection
