<div class="portlet" ng-hide="loading">
  	<div class="portlet-head">
	    <div class="row">
		    <div class="col-md-6">
			    <ul class="menu">
			        <li class="active">
			           	<a href="#">List of Students</a>
			        </li>
			    </ul>
		    </div>
		</div>
	</div>
	<div class="portlet-body">

		<div ng-show="loading" class="alert alert-warning container">
			Loading...
		</div>
		<div ng-show="students.length == 0 && !loading" class="alert alert-danger container">
			No Data Found
		</div>

		<div class="row" ng-if="!loading && students.length > 0">
			<div class="col-md-7">
				<b>Selected Students</b>
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
				<b>Removed from List</b>
				<div ng-show="show_removed_list" style="margin-top: 10px">
					<span  class="btn btn-default" style="margin-right: 5px;margin-top: 5px" ng-repeat="student in removed_students">@{{student.name}} &nbsp;&nbsp;&nbsp;<button ng-click="addStudentToList(student,$index)" class="btn btn-info btn-xs">+</button></span>
				</div>
			</div>
		</div>
	</div>
</div>