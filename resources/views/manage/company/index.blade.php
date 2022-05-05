@extends('layout')

<div class="" ng-controller="Company_controller" ng-init="init()">
	@section('sub_header')
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">Companies</h4>
				</div>
				<div class="text-right">
					<button class="btn btn-primary" ng-click="addCompany()">Add company</button>	
				</div>
			</div>
		</div>
	@endsection

@section('content')
		
	<div class="portlet">
		
		<div class="portlet-body ng-cloak">

			<div table-paginate></div>

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
		 					<th>#</th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<tr ng-repeat="data in dataset track by $index">
		 					<td>@{{$index+1}}</td>
		 					<td>@{{data.company_name}}</td>
		 					<td>@{{data.contact_no}}</td>
		 					<td>@{{data.address}}</td>
		 					<td>
		 						<button type="button" class="btn btn-sm btn-primary" ng-click="editCompany(data)">Edit</button>&nbsp;&nbsp;
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

@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/Company_controller.js?v='.env('JS_VERSION')) }}" ></script>
@endsection
