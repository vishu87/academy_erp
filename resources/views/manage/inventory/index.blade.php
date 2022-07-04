@extends('layout')

<div class="" ng-controller="Inventory_controller" ng-init="init()">
	@section('sub_header')
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">Inventory Items</h4>
				</div>
				<div class="text-right">
					
				</div>
			</div>
		</div>
	@endsection

@section('content')
		
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
		        	<button class="btn btn-primary" ng-click="addItem()"><i class="icons icon-plus"></i> Add Item</button>	
		        </div>

	      	</div>
	    </div>


		<div class="portlet-body ng-cloak">
			<div class="table-responsive" ng-if="!loading && dataset.length > 0">
				<table class="table">
		 			<thead>
		 				<tr>
		 					<th>SN</th>
		 					<th>Item Name</th>
		 					<th>Min Quantity</th>
		 					<th>Unit</th>
		 					<th class="text-right">#</th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<tr ng-repeat="data in dataset track by $index">
		 					<td>@{{$index+1}}</td>
		 					<td>@{{data.item_name}}</td>
		 					<td>@{{data.min_quantity}}</td>
		 					<td>@{{data.unit}}</td>
		 					<td class="text-right">
		 						<button type="button" class="btn-light btn btn-sm" ng-click="editItem(data)">Edit</button>&nbsp;&nbsp;
            					<button type="button" class="btn-danger btn btn-sm" ng-click="deleteItem(data.id, $index)">Delete</button>
		 					</td>
		 				</tr>
		 			</tbody>
			 	</table>
			</div>
		</div>
	</div>
	@include('manage.inventory.item_modal')
</div>

@endsection
