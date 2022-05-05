@extends('layout')

<div class="" ng-controller="communicationCtrl" ng-init="listing()">
@section('sub_header')
	<div class="sub-header">
		<div class="table-div full">
			<div>
				<h4 class="fs-18 bold" style="margin:0;">Communications</h4>
			</div>
		</div>
	</div>
@endsection

@section('content')
		
	<div class="container-fluid filters small-form">
		
		<div ng-show="loading" class="alert alert-warning container" style="margin-top: 50px">
			Loading...
		</div>
		<div ng-show="noDataFound" class="alert alert-danger container" style="margin-top: 50px">
			No Data Found
		</div>

		<div class="row">
			<div class="col-md-12">
				
				<div ng-show="communications.length > 0" style="margin-top: 10px;">

					<div class="row">
						<div class="col-md-6 text-left">
							Total - @{{count}} | Showing @{{ ((pn-1)*max + 1) + ' - ' }} @{{(pn*max < count) ? pn*max : count}}
						</div>
						<div class="col-md-6 text-right">
							<a href="javascript:;" ng-click="prevPage()">Prev</a>
								| @{{pn}} of @{{total_pn}} |
							<a href="javascript:;" ng-click="nextPage()">Next</a>

						</div>
					</div>
					
					<div class="ng-cloak table-responsive" ng-show="communications.length > 0 && !loading" style="overflow-y: auto;">
						<table class="table  ">
							<tr>
								<th>SN</th>
								<th>Send Type</th>
								<th>SMS Type</th>
								<th>Subject</th>
								<th>Message</th>
								<th>Date</th>
								<th>Added By</th>
								<th></th>
							</tr>
							
							<tbody>
								<tr ng-repeat="comm in communications">
									<td>@{{ (pn-1)*max + $index + 1}}</td>
									<td>
										@{{comm.send_types}}
									</td>
									<td>
										@{{comm.sms_types}}
									</td>
									<td>
										@{{comm.subject}}
									</td>
									<td>
										@{{comm.message_show}}
									</td>
									<td>
										@{{comm.c_date}}
									</td>
									<td>
										@{{comm.name}}
									</td>
									<td>
										<button type="button" class="btn btn-primary" ng-click="viewStudetns(comm)" >View Details</button>
									</td>

								</tr>
							</tbody>
						</table>	
					</div>	
				</div>
			</div>
			<div class="col-md-5">
				

				<div ng-show="show_removed_list" style="margin-top: 10px">

					<span  class="btn btn-default" style="margin-right: 5px;margin-top: 5px" ng-repeat="student in removed_students">@{{student.name}} &nbsp;&nbsp;&nbsp;<button ng-click="addStudentToList(student,$index)" class="btn btn-info btn-xs">+</button></span>
				</div>
			</div>
		</div>
	</div>
				
	<div class="modal fade in" id="showNumber" role="dialog" >
		<div class="modal-dialog modal-small">
		    <div class="modal-content">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		        <div class="modal-body">
		            <div style="font-size:32px;" class="text-center">
		                @{{mobile_show}}
		            </div>
		            
		        </div>
		    </div>
		</div>
	</div>

	<div class="modal fade in" id="students" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg">
		    <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Communication Students</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>


		        <div class="modal-body">
		            
					<table class="table table-bordered">
						<tr>
							<td><strong>Send Type :</strong> @{{open_comm.send_types}}</td>
							<td><strong>SMS Type :</strong> @{{open_comm.sms_types}}</td>
							<td><strong>Added By :</strong>@{{open_comm.name}}</td>
						</tr>
						<tr>
							<td><strong>Subject :</strong>@{{open_comm.subject}}</td>
							<td colspan="2"><strong>SMS Content :</strong>@{{open_comm.sms_content}}</td>
						</tr>
						<tr>
							<td colspan="3">
								<strong>Message :</strong>
								<div ng-bind-html="open_comm.message"></div>
							</td>
						</tr>
					</table>
					<div><hr></div>
					<div ng-show="loading_students"> Loading Students ...</div>
					<div style="margin-top: 10px" ng-show="!loading_students">
						<div class="row">
							<div class="col-md-6 text-left">
								Total - @{{count}} | Showing @{{ ((comm_pn-1)*100 + 1) + ' - ' }} @{{(comm_pn*100 < count) ? comm_pn*100 : count}}
							</div>
							<div class="col-md-6 text-right">
								<a href="javascript:;" ng-click="prevPageComm()">Prev</a>
									| @{{comm_pn}} of @{{total_comm_pn}} |
								<a href="javascript:;" ng-click="nextPageComm()">Next</a>

							</div>
						</div>
						<table class="table  table-compact table-bordered table-stripped">
							<tr>
								<th>SN</th>
								<th>Name</th>
								<th>DOB</th>
								<th>Subscription End</th>
								<th>Mobile</th>
								<th>Center</th>
								<th>Group</th>
							</tr>
							<tbody>
								<tr ng-repeat="student in open_comm.students">
									<td>@{{$index + 1}}</td>

									<td><span style="display: block;">@{{student.name}}</span></td>
									<td>@{{student.dob}}</td>
									<td>@{{student.doe}}</td>
									<td style="cursor: pointer;" ng-click="showNumber(student.father_mob)">
										<a href="javascript:;">@{{student.mobile_trimmed}}</a>
									</td>
									<td>@{{student.center_name}}</td>
									<td>@{{student.group_name}}</td>
								</tr>
							</tbody>
						</table>
					</div>			            
		        </div>
		    </div>
		</div>
	</div>
</div>

@endsection

@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/communicationCtrl.js?v='.env('JS_VERSION')) }}" ></script>
@endsection
