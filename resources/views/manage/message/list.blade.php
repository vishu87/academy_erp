@extends('layout')

<div class="" ng-controller="communicationListCtrl" ng-init="listing()">
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
		
	<div class="portlet">
		
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
		        </div>

	      	</div>
	    </div>

		<div class="portlet-body ng-cloak">
			<div class="table-responsive" ng-if="!loading && communications.length > 0">
				<table class="table table-compact">
					<thead>
						<tr>
							<th>SN</th>
							<th>Date</th>
							<th>Type</th>
							<th>Subject</th>
							<th>Message</th>
							<th>Added By</th>
							<th></th>
						</tr>
					</thead>
							
					<tbody>
						<tr ng-repeat="comm in communications">
							<td>@{{ (pn-1)*max + $index + 1}}</td>
							<td>@{{comm.c_date}}</td>
							<td>@{{comm.send_types}}</td>
							<td>@{{comm.subject ? comm.subject : '-'}}</td>
							<td>@{{comm.message_show}}</td>
							<td>@{{comm.name}}</td>
							<td><button type="button" class="btn btn-sm btn-light" ng-click="viewStudetns(comm)" >View Details</button></td>
						</tr>
					</tbody>
				</table>	
			</div>	
		</div>
	</div>

	<div class="modal fade in" id="students" role="dialog" >
		<div class="modal-dialog modal-lg">
		    <div class="modal-content">

	        <div class="modal-header">
	            <h4 class="modal-title">Details</h4>
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
	        </div>


		        <div class="modal-body">
					<table class="table table-compact">
						<tr>
							<td><strong>Send Type :</strong> @{{open_comm.send_types}}</td>
							<td><strong>Added By :</strong>@{{open_comm.name}}</td>
						</tr>
						<tr>
							<td colspan="2"><strong>Subject :</strong>@{{open_comm.subject}}</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong>Content :</strong>
								<div>@{{ open_comm.content }}</div>
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
						<div class="table-responsive">
							<table class="table table-compact table-bordered table-stripped">
								<tr>
									<th>SN</th>
									<th>Name</th>
									<th>DOB</th>
									<th>Subscription End</th>
									<th>Center</th>
									<th>Group</th>
									<th>Status</th>
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
										<td>@{{student.status == 0 ? 'Pending' : 'Sent'}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>	
					<div class="modal-footer">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-primary" > Close </button>
            </div>		            
		        </div>
		    </div>
		</div>
	</div>
	
</div>

@endsection

@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/communicationListCtrl.js?v='.env('JS_VERSION')) }}" ></script>
@endsection
