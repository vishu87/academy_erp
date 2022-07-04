@extends('layout')

@section('content')
<!-- events -->
<div class="ng-cloak" ng-controller="holidays_controller" ng-init="init()">
	<div class="page-header row">
		<div class="col-md-6"><h3>Holidays</h3></div>
		<div class="col-md-6 text-right">
			<a href="#" class="btn btn-primary" ng-click="add()"><i class="icons icon-plus"></i> Add</a>
		</div>
	</div>
		
	<div class="portlet">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6">
					<ul class="menu">
						<li class="active">
							<a href="#">Holidays List</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="portlet-body ng-cloak">
			
			<div class="table-cont">

				<table class="table">
					<thead>
						<tr>
							<th>Sn</th>
							<th>Name</th>
							<th>Date</th>
							<th style="width: 120px;" class="text-right">#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="holiday in holidays track by $index">
							<td>@{{$index+1}}</td>
							<td>@{{holiday.name}}</td>
							<td>@{{holiday.date}}</td>
							<td class="text-right">
								<a href="#" ng-click="edit(holiday)" class="btn btn-light btn-sm">Edit</a> 
								<button class="btn btn-danger btn-sm" ng-click="delete(holiday.id, $index)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
				
			</div>

		</div>
	</div>
	@include('manage.holiday.holiday_modal')
</div>
@endsection
