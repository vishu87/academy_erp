
	<li class="parent">
		<a href="{{url('/students')}}">
			<i class="icon-people icons "></i>
			<span>Students</span>
		</a>
		<ul>
			<li>
				<a href="{{url('/students')}}">
					List
				</a>
			</li>
			<li>
				<a  href="{{url('/students/attendance')}}">
					Attendance
				</a>
			</li>
	    </ul>
	</li>

	<li class="parent">
		<a  href="{{url('/students/performance/sessions')}}">
			<i class="icon-hourglass icons "></i>
			<span>Performance</span>
		</a>
		<ul>
			<li>
				<a  href="{{url('/students/performance/sessions')}}">
					Create Session
				</a>
			</li>
	      	<li>
	      		<a href="{{url('students/performance')}}">
	      			Record Data
	      		</a>
	      	</li>
	    </ul>
	</li>

	<li class="parent">
		<a>
			<i class="icon-calculator icons "></i>
			<span>Payments</span>
		</a>
		<ul>
			<li><a href="{{url('payments')}}" >List of Payments</a></li>
	      	<li><a href="{{url('/pay-type-category')}}">Categories</a></li>
	      	<li><a href="{{url('pay-type-price')}}" >Pricing Structure</a></li>
	    </ul>
	</li>
	
	<li class="parent">
	    <a><i class="icon-people icons "></i> <span>Users</span></a>
	    <ul>
	      	<li>
	      		<a href="{{url('users/view')}}" style="width: 150px;">
	      		All Users</a>
	      	</li>
	      	<!-- <li><a href="{{url('users/add')}}" style="width: 150px;">Add Users</a></li> -->
	      	<li><a href="{{url('users/user-roles')}}" style="width: 150px;">Role Manager</a></li>
	      	<li>
	      		<a href="{{url('users/staff-attendance')}}" style="width: 150px;">Staff Attendance</a>
	      	</li>
	      	<!-- <li><a href="{{url('users/user-rights')}}" style="width: 150px;">Access Rights</a></li> -->
	    </ul>
	</li>

	<li class="parent" style="display: none">
		<a href=""><i class="icon-event icons "></i> <span>Events</span></a>
		<ul>
		  <li><a href="{{url('/events')}}" style="width: 150px;">Events</a></li>
		</ul>
	</li>

	<li class="parent" style="display: none">
		<a href=""><i class="icon-feed icons "></i> <span>Leads</span></a>
		<ul>
		  <li><a href="{{url('/leads')}}" style="width: 150px;">Leads</a></li>
		</ul>
	</li>

	<li class="parent" style="display: none">
		<a href=""><i class="icon-envelope-open icons "></i> <span>Contact</span></a>
	</li>

	<li class="parent" style="display: none">
		<a href=""><i class="icon-screen-smartphone icons "></i> <span>App</span></a>
	</li>

	<li class="parent">
	    <a><i class="icon-settings icons "></i> <span>Admin</span></a>
	    <ul>
	      <li><a href="{{url('/city')}}" style="width: 200px;">Cities</a></li>
		  <li><a href="{{url('/centers')}}" style="width: 200px;">Centers & Batches</a></li>
		  <li><a href="{{url('/acounts')}}" style="width: 200px;">Tax Settings</a></li>
		  <!-- <li><a href="{{url('/inventory')}}" style="width: 200px;">Inventory</a></li> -->
	    </ul>
		</li>
		
	<li class="parent" style="display: none">
	    <a><i class="icon-options icons "></i> <span>More</span></a>
	    <ul>
	      <li><a href="{{url('/clients')}}" style="width: 200px;">Add Client</a></li>
	    </ul>
	</li>