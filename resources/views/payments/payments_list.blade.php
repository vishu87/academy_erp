@extends('layout')

@section('sub_header')
	<div class="sub-header">
		<div class="table-div full">
			<div>
				<h4 class="fs-18 bold" style="margin:0;">Payments</h4>
			</div>
			<div class="text-right">
				
			</div>
		</div>
	</div>
@endsection
 
@section('content')


	<div class="ng-cloak" ng-controller="payments_controller">

		<div class="portlet" >
			<div class="portlet-body">

				<div table-paginate></div>

				<div class="filters" ng-if="filter.show">
					<form name="filterForm" ng-submit="" novalidate>
						<div class="row" style="font-size: 14px">

							<div class="col-md-2 form-group">
								<label class="label-control">Student Name</label>
								<input type="text" class="form-control" ng-model="filter.student_name" />
							</div>

							<div class="col-md-2 form-group">
								<label class="label-control">Date From</label>
								<input type="text" class="form-control datepicker" ng-model="filter.start_date">
							</div>

							<div class="col-md-2 form-group">
								<label class="label-control">Date To</label>
								<input type="text" class="form-control datepicker" ng-model="filter.end_date" />
							</div>

						</div>
						<div>
							<button ng-click="searchList()" class="btn btn-primary">Apply</button>
						</div>
					</form>
				</div>

				<div ng-if="loading" class="text-center mt-5 mb-5">
		      <div class="spinner-grow" role="status">
		        <span class="sr-only">Loading...</span>
		      </div>
		    </div>

				<div ng-if="dataset.length == 0 && !loading">
					@include('common.no_found',["pay_history" => true,"message" => "No Payment Details found"])
				</div>

				<div class="table-cont"> 
					<table class="table  table-hover">
						<thead>
							<tr class="">
								<th>SN</th>
								<th>Code</th>
								<th>Student Name</th>
								<th>Invoice Date</th>
								<th>Payment Date</th>
								<th>Amount</th>
								<th>Tax</th>
								<th>Total Amount</th>
								<th>Created At</th>
								<th class="text-right">#</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="item in dataset track by $index">
								<td>@{{ (filter.page_no - 1)*filter.max_per_page + $index + 1 }}</td>
								<td class="theme-color" style="cursor:pointer;" ng-click="viewPayment(item.id)">@{{item.code}}</td>
								<td class="theme-color" style="cursor:pointer;" ng-click="studentDetail(item.student_id)">
									@{{item.name}}
								</td>
								<td>@{{item.invoice_date}}</td>
								<td>@{{item.payment_date}}</td>
								<td>@{{item.amount}}</td>
								<td>@{{item.tax}}</td>
								<td>@{{item.total_amount}}</td>
								<td>@{{item.created_at | date}}</td>
								<td class="text-right">
									<button class="btn btn-sm btn-light" ng-click="viewPayment(item.id)">View</button>
									<a href="{{url('/payment-receipt')}}/@{{item.unique_id}}" class="btn btn-sm btn-primary" target="_blank"><i class="icons icon-printer"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@include('payments.view_payment_modal')
		@include('students.student_personal_detail_modal')
	</div>

@endsection

