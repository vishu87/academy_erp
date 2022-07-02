@extends('layout')

@section('content')
<div class="ng-cloak" ng-controller="smsTempCtrl" ng-init="init()">

	<div class="page-header row">
		<div class="col-md-6 col-8">

			<h3>SMS Templates</h3>
		</div>
		<div class="col-md-6 col-4 text-right">
			<button class="btn btn-primary" type="button" ng-click="add()"><i class="icons icon-plus"></i> Add</button>
		</div>
	</div>
		
	<div class="portlet">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6">
					<ul class="menu">
						<li class="active">
							<a href="#">List of Templates</a>
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
							<th>SN</th>
							<th>Name</th>
							<th>For</th>
							<th>Template</th>
							<th>DLT Template ID</th>
							<th>DLT Sender ID</th>
							<th>DLT PE ID</th>
							<th>#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="template in templates">
							<td>@{{$index+1}}</td>
							<td>@{{template.name}}</td>
							<td>
								@{{template.type==1?'Promotional':''}}
								@{{template.type==2?'Transactional':''}}
							</td>
							<td>@{{template.template}}</td>
							<td>@{{template.dlt_template_id}}</td>
							<td>@{{template.dlt_sender_id}}</td>
							<td>@{{template.dlt_pe_id}}</td>
							<td style="width: 120px">
								<button class="btn btn-sm btn-light" type="button" ng-click="edit(template)">Edit</button>
								<button class="btn btn-sm btn-danger" type="button" ng-click="delete(template,$index)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
				
			</div>

		</div>
	</div>
	@include('manage.template.add_template_modal')
</div>
@endsection