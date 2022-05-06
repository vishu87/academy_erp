@extends('layout')

@section('content')
<div class="" ng-controller="master_leads_controller" ng-init="init()">
	<div class="page-header row">
		<div class="col-6">
			<h3>Master Leads</h3>
		</div>
	</div>
		<div class="row">
			<div class="col-md-4">
				@include('manage.masterLeads.lead_for')
			</div>
			<div class="col-md-4">
				@include('manage.masterLeads.lead_status')
			</div>
			<div class="col-md-4">
				@include('manage.masterLeads.lead_reasons')
			</div>
			<div class="col-md-4">
				@include('manage.masterLeads.lead_sources')
			</div>
		</div>
	@include('manage.masterLeads.master_leads_modal')
</div>
@endsection

@section('footer_scripts')
  <script type="text/javascript" 
  src="{{url('assets/plugins/admin/scripts/core/leads/master_leads_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection
