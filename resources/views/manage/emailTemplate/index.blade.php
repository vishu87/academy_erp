@extends('layout')

@section('content')
<div class="ng-cloak" ng-controller="emailTempCtrl" ng-init="init()">

	<div class="page-header row">
		<div class="col-md-6">

			<h3>Email Templates</h3>
		</div>
		<div class="col-md-6 text-right">
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
							<th>Template</th>
							<th>Content</th>
							<th>#</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="template in templates">
							<td>@{{$index+1}}</td>
							<td>@{{template.template_name}}</td>
							<td>@{{template.content}}</td>
							<td width="120px">
								<button class="btn btn-sm btn-light" type="button" ng-click="edit(template)">Edit</button>
								<button class="btn btn-sm btn-danger" type="button" ng-click="delete(template.id,$index)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
				
			</div>

		</div>
	</div>

	@include('manage.emailTemplate.add_template_modal')
</div>
@endsection
