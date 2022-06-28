@extends('layout')

@section('content')
<div class="" ng-controller="leads_controller" ng-init="init()">

	<div class="page-header row">
		<div class="col-6">
			<h3>Leads</h3>
		</div>
		<div class="col-6">
			<div class="text-right">
				<button class="btn btn-primary" ng-click="addLead()"><i class="icons icon-plus"></i> Add Lead</button>
			</div>
		</div>
	</div>

	<div class="portlet">
		<div class="portlet-body ng-cloak">

			<div table-paginate></div>

			@include("manage.leads.filters")

			<div ng-show="dataset.length > 0 && !loading" style="overflow-y: auto;">
				<table class="table table-compact">
					<thead>
						<tr>
							<th>SN</th>
							<th> <input type="checkbox" ng-click="checkAll()" ng-checked="formData.checkAll"> </th>
							<th><th-sort column-id="name" column-name="Name"></th>
							<th><th-sort column-id="age" column-name="Age"></th>
							<th>Mobile</th>
							<th><th-sort column-id="center_name" column-name="Center"></th>
							<th><th-sort column-id="source" column-name="Lead Source"></th>
							<th><th-sort column-id="status_value" column-name="Lead Status"></th>
							<th><th-sort column-id="action_date" column-name="Action Date"></th>
							<th><th-sort column-id="assigned_member" column-name="Assigned To"></th>
							<th>Last Call Note</th>
							<th><th-sort column-id="updated_at" column-name="Last Updated"></th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="lead in dataset track by $index">
							<td>@{{ (filter.page_no-1)*filter.max_per_page + $index + 1}}</td>
							<td>
								<input type="checkbox" ng-checked="selectedLeads.indexOf(lead.id) > -1" ng-click="addSelectedLead(lead)">
							</td>
							<td class="theme-color"> <b>@{{lead.name}}</b> </td>
							<td> @{{lead.age}} </td>
							<td style="cursor: pointer;" ng-click="showInfo(lead.mobile, 'mobile')"> @{{lead.mobile_trimmed}} </td>
							<td>@{{lead.center_name}} ( @{{lead.group_name}} )</td>
							<td>@{{lead.source}}</td>
							<td>
								<span class="badge" style="background: @{{lead.status_color}}; color: #FFF">
									@{{lead.status_value}}
								</span>
							</td>
							<td>@{{lead.action_date | date}}</td>
							<td>@{{lead.assigned_member}}</td>
							<td>
								<span>@{{lead.last_call_note}}</span><br>
								<a href="javascript:;" ng-click="addNote($index)" style="text-decoration: underline;">Add Update	</a>
							</td>
							<td>@{{lead.updated_at}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div ng-show="dataset.length == 0 && !loading" class="alert alert-warning mt-5">
				No leads found
			</div>
			<hr>
			<div class="row" ng-if="dataset.length > 0 && !loading">
		    <div class="col-md-6">
		    		Total Leads Selected = @{{selectedLeads.length}}
		    		<button class="btn btn-light" type="button" ng-click="selectAllFilterLeads()" ng-disabled="selecting_all_leads" ng-if="total < 500">Select All @{{total}} <span ng-show="selecting_all_leads" class="spinner-border spinner-border-sm"></button>
		    </div>
	      <div class="col-md-6 text-right" ng-if="selectedLeads.length > 0 && !loading && !selecting_all_leads">
						<button class="btn btn-primary" type="button" ng-click="sendMessage()">Send Message</button>		
				</div>
		  </div>

		</div>
	</div>

	@include('manage.leads.leads_add_modal')
	@include('manage.leads.leads_edit_modal')
	@include('manage.leads.leads_modal')

</div>
@endsection

