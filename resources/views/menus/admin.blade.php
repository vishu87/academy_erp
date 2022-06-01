<li class="parent @if($sidebar == 'city') active @endif ">
    <a href="{{url('/city')}}"><i class="icon-settings icons "></i> <span>City</span></a>
</li>

<li class="parent @if($sidebar == 'center') active @endif">
    <a href="{{url('/centers')}}"><i class="icon-settings icons "></i> <span>Centers</span></a>
</li>


<li class="parent @if($sidebar == 'users') active @endif">
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


<li class="parent @if($sidebar == 'parameter') active @endif">
    <a href="{{url('/parameters')}}"><i class="icon-settings icons "></i> <span>Parameters</span></a>
</li>

<li class="parent @if($sidebar == 'settings') active @endif">
    <a href="{{url('/settings')}}"><i class="icon-settings icons "></i> <span>Settings</span></a>
</li>

<!-- <li class="parent @if($sidebar == 'reports') active @endif">
    <a><i class="icon-list icons "></i> <span>Reports</span></a>
    <ul>
        <li>
            <a href="{{url('reports/center')}}" style="width: 150px;">
            Center Revenue</a>
        </li>
        <li>
            <a href="{{url('reports/sales')}}" style="width: 150px;">
            Sales</a>
        </li>
        <li>
            <a href="{{url('reports/students')}}" style="width: 150px;">
            Students</a>
        </li>
        <li>
            <a href="{{url('reports/leads')}}" style="width: 150px;">
            Leads</a>
        </li>
    </ul>
</li> -->