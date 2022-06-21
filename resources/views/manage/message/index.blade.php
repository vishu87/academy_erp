@extends('layout')
@section('sub_header')
	<div class="ng-cloak" ng-controller="communicationCtrl" ng-init="filter.only_active = $only_active; init()">
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">Send Message</h4>
				</div>
				<div class="text-right">
					<div>Total Selected: <b>@{{count}}</b></div>
			    	<div>Total Removed: <b>@{{removed_students.length}}</b></div>
				</div>
				<div ng-if="students.length > 0 && !loading" style="width: 150px" class="text-right">
					<button class="btn btn-primary" type="button" ng-click="sendMessage()">Send Message</button>
				</div>
			</div>
		</div>
		@endsection

		@section('content')
		<div class="row">
			<div class="col-md-4">
				@include('manage.message.filter')
			</div>
			<div class="col-md-8">
				@include('manage.message.show_student')
			</div>
		</div>
		@include('manage.message.message_modal')
		@include('manage.message.remove_student_modal')
	</div>
@endsection

@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/communicationCtrl.js?v='.env('JS_VERSION')) }}" ></script>
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/sendMessageCtrl.js?v='.env('JS_VERSION')) }}" ></script>
@endsection
