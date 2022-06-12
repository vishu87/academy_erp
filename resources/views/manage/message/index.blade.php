@extends('layout')
@section('sub_header')
	<div class="ng-cloak" ng-controller="communicationCtrl" ng-init="filter.only_active = $only_active; init()">
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">Send Message</h4>
				</div>
				<div class="text-right">
					<div >
						<button class="btn btn-primary" ladda="loading" ng-click="getStudents(1)">Search</button>
					</div>
					<div >
						<div>Total Students Selected = @{{count}}  </div>
				    	<div>Total Students Removed = @{{removed_students.length}}</div>  
				        <div ng-if="students.length > 0 && !loading" style="margin-top: 10px">
							<button class="btn btn-primary" type="button" ng-click="sendMessage()">Send Message</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endsection

		@section('content')
		<div class="row">
			<div class="col-md-3">
				@include('manage.message.filter')
			</div>
			<div class="col-md-9">
				@include('manage.message.show_student')
			</div>
		</div>
		@include('manage.message.message_modal')
	</div>
@endsection

@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/communicationCtrl.js?v='.env('JS_VERSION')) }}" ></script>
@endsection
