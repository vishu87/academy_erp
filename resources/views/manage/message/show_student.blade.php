<div class="portlet" ng-hide="loading">
  	<div class="portlet-head">
	    <div class="row">
		    <div class="col-md-6">
			    <ul class="menu">
			        <li class="active">
			           	<a href="#">Students</a>
			        </li>
			    </ul>
		    </div>
		</div>
	</div>
	<div class="portlet-body">
		<div ng-if="!loading" >
			<div class="row">
				<div class="col-md-7">
			 		<button ng-if="students.length > 0" class="btn btn-sm btn-primary" type="button" ng-click="toggleList()">@{{show_list ? 'Hide':'Show'}} Selected Students</button>
				</div>

				<div class="col-md-5">
					<button ng-if="removed_students.length > 0" class="btn btn-sm btn-primary" type="button" ng-click="toggleRemovedList()">@{{show_removed_list ? 'Hide':'Show'}} Removed Students</button>
				</div>
			</div>
		</div>

		<div ng-show="loading" class="alert alert-warning container">
			Loading...
		</div>
		<div ng-show="noDataFound" class="alert alert-danger container">
			No Data Found
		</div>

		<div class="row">
			<div class="col-md-7">
				<div ng-show="show_list">
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
					
					<div ng-show="students.length > 0 && !loading" >
						<table class="table  table-compact table-bordered table-stripped">
							<tr>
								<th>SN</th>
								<th style="cursor: pointer;"  ng-click="sortBy('name')">Name <span ng-if=" sort_by == 'name' && sorting == 'ASC' "><i class="fa fa-angle-up"></i></span> <span ng-if=" sort_by == 'name' && sorting == 'DESC' "><i class="fa fa-angle-down"></i></span> </th>

								<th>DOB</th>
								<th>Subscription End</th>
								<th>Mobile</th>
								<th style="cursor: pointer;"  ng-click="sortBy('center_name')">Center <span ng-if=" sort_by == 'center_name' && sorting == 'ASC' "><i class="fa fa-angle-up"></i></span> <span ng-if=" sort_by == 'center_name' && sorting == 'DESC' "><i class="fa fa-angle-down"></i></span> </th>
								<th>Remove</th>
							</tr>
							
							<tbody>
								<tr ng-repeat="student in students">
									<td>@{{ (pn-1)*max + $index + 1}}</td>

									<td>
										<span style="display: block;">@{{student.name}}</span>
									</td>

									<td>
										@{{student.dob}}
									</td>
									<td>
										@{{student.doe}}
									</td>

									<td style="cursor: pointer;" ng-click="showNumber(student.father_mob)">
										<a href="javascript:;">
											@{{student.mobile_trimmed}}
										</a>
									</td>

									<td >
										@{{student.center_name}}
										
									</td>
									<td>
										<button type="button" class="btn btn-danger" ng-click="removeStudent(student,$index)" ladda="student.delete">X</button>
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
</div>