<div class="portlet" ng-hide="loading">
  	<div class="portlet-head">
	    <div class="row">
		    <div class="col-md-6">
			    <ul class="menu">
			        <li class="active">
			           	<a href="#">Selected Students</a>
			        </li>
			    </ul>
		    </div>
		    <div class="col-md-6 text-right">
		    	<button class="btn btn-sm btn-light" ng-if="removed_students.length > 0" ng-click="showRemovedStudents()" style="margin-top: 10px;">Show removed students (@{{removed_students.length}})</button>
		    </div>
		</div>
	</div>
	<div class="portlet-body">

		<div ng-show="loading" class="alert alert-warning">
			Loading...
		</div>
		<div ng-show="students.length == 0 && !loading" class="alert alert-danger">
			No Data Found
		</div>

		<div class="row" ng-if="!loading && students.length > 0">
			<div class="col-md-12">
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
					
					<div class="table-responsive" ng-show="students.length > 0 && !loading" >
						<table class="table table-compact">
							<thead>
								<tr>
									<th>SN</th>
									<th style="cursor: pointer;"  ng-click="sortBy('name')">Name <span ng-if=" sort_by == 'name' && sorting == 'ASC' "><i class="fa fa-angle-up"></i></span> <span ng-if=" sort_by == 'name' && sorting == 'DESC' "><i class="fa fa-angle-down"></i></span> </th>
									<th>DOB</th>
									<th>Subscription End</th>
									<th style="cursor: pointer;"  ng-click="sortBy('center_name')">Center <span ng-if=" sort_by == 'center_name' && sorting == 'ASC' "><i class="fa fa-angle-up"></i></span> <span ng-if=" sort_by == 'center_name' && sorting == 'DESC' "><i class="fa fa-angle-down"></i></span> </th>
									<th>Remove</th>
								</tr>
							</thead>
							
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
									<td >
										@{{student.center_name}}
									</td>
									<td>
										<button type="button" class="btn btn-danger btn-sm" ng-click="removeStudent(student,$index)" ladda="student.delete">Remove</button>
									</td>

								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>