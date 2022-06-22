@extends('layout')

@section('sub_header')
  <div class="sub-header">
    <div class="table-div">
      <div>
        <h4 class="fs-18 bold" style="margin:0;">Performance Sessions</h4>
      </div>
      <div class="text-right">
      </div>
    </div>
  </div>
@endsection

@section('content')

<div class="ng-cloak" ng-controller="Stu_Performance_Controller" ng-init="getSessionList();"> 

    <div ng-if="loading" class="text-center mt-5 mb-5">
      <div class="spinner-grow" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

  	<div class="portlet" ng-if="!loading">

	    <div class="portlet-head">
	      	<div class="row">

		        <div class="col-md-6">
		          	<ul class="menu">
			            <li class="active">
			              <a href="#">List</a>
			            </li>
		          	</ul>
		        </div>
		        <div class="col-md-6 text-right">
		        	<button class="btn btn-primary" ng-click="addSession()"><i class="icons icon-plus"></i> Add</button>
		        </div>

	      	</div>
	    </div>

    	<div class="portlet-body ng-cloak">
      	
      	<div class="table-responsive">
	    		<table class="table table-compact">
			 			<thead>
			 				<tr class="">
			 					<th style="width:50px;">SN</th>
			 					<th>Name</th>
			 					<th>Start Date</th>
			 					<th>End Date</th>
			 					<th class="text-right">#</th>
			 				</tr>
			 			</thead>
			 			<tbody>
			 				<tr ng-repeat="session in sessionList track by $index">
			 					<td>@{{$index + 1}}</td>
			 					<td>@{{session.name}}</td>
			 					<td>@{{session.start_date}}</td>
			 					<td>@{{session.end_date}}</td>
			 					<td class="text-right">
			 						<button class="btn btn-sm btn-light" ng-click="editSession(session)">Edit</button>
			 						<button class="btn btn-sm btn-primary" ng-click="deleteSession(session.id)">Delete</button>
			 					</td>
			 				</tr>
			 			</tbody>
		 			</table>
	 			</div>

    	</div>

  	</div>

  	@include('students.performance.performance_modal')

</div>

@endsection

@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/students_performance_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection
