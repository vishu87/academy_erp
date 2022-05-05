<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Contact Persons</a>
					</li>
				</ul>
			</div>
			<div class="col-md-6 text-right">
				<button type="button" class="btn btn-success" ng-click="addContactPerson()" >Contact Person</button>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">

	    <div class="col-md-12" style="margin-top: 10px;">
			<table class="table" ng-show="center.contact_persons.length > 0">
				<tr>
					<td>Name</td>
					<td>Designation</td>
					<td>Email</td>
					<td>Mobile</td>
					<td></td>
				</tr>
				<tr ng-repeat="member in center.contact_persons">
					<td>@{{member.member_name}}</td>
					<td>@{{member.designation}}</td>
					<td>@{{member.email}}</td>
					<td>@{{member.mobile}}</td>
					<td><button type="button" ng-click="removePerson($index)" class="btn btn-sm btn-danger pull-right">X</button></td>
					
				</tr>
			</table>
		</div>

	</div>

</div>