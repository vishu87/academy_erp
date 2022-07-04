@extends('layout')

<div class="" ng-controller="Company_controller" ng-init="init()">
	@section('sub_header')
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">Companies</h4>
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

		        <div class="col-md-6 col-4">
		          	<ul class="menu">
			            <li class="active">
			              <a href="#">List</a>
			            </li>
		          	</ul>
		        </div>
		        <div class="col-md-6 col-8 text-right">
					<button class="btn btn-primary" ng-click="addCompany()"><i class="icons icon-plus"></i> Add company</button>	
		        </div>

	      	</div>
	    </div>
		
		<div class="portlet-body ng-cloak">

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
		 					<th>Company Name</th>
		 					<th>Contact Number</th>
		 					<th>Address</th>
		 					<th class="text-right">#</th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<tr ng-repeat="data in dataset track by $index">
		 					<td>@{{$index+1}}</td>
		 					<td>@{{data.company_name}}</td>
		 					<td>@{{data.contact_no}}</td>
		 					<td>@{{data.address}}</td>
		 					<td class="text-right">
		 						<button type="button" class="btn btn-sm btn-light" ng-click="editCompany(data)">Edit</button>&nbsp;&nbsp;
            					<button type="button" class="btn btn-sm btn-danger" ng-click="deleteCompany(data.id, $index)">Delete</button>
		 					</td>
		 				</tr>
		 			</tbody>
			 	</table>
			</div>
		</div>
	</div>
	@include('manage.company.company_modal')
</div>

@endsection

