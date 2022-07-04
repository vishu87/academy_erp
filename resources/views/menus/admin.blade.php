<?php $access_rights = Session::get("access_rights"); ?>

<?php $condition = in_array(25, $access_rights["admin"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'city') active @endif ">
    <a href="{{url('/city')}}"><i class="icon-settings icons "></i> <span>City</span></a>
</li>
@endif

<?php $condition = in_array(15, $access_rights["admin"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'center') active @endif">
    <a href="{{url('/centers')}}"><i class="icon-settings icons "></i> <span>Centers</span></a>
</li>
@endif


<?php $condition = in_array(5, $access_rights["admin"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'users') active @endif">
    <a><i class="icon-people icons "></i> <span>Users</span></a>
    <ul>
        <?php $condition = in_array(5, $access_rights["admin"]) ; ?>
        @if($condition)
      	<li>
      		<a href="{{url('users/view')}}" style="width: 150px;">
      		All Users</a>
      	</li>
        @endif

        <?php $condition = in_array(25, $access_rights["admin"]) ; ?>
        @if($condition)
      	<li><a href="{{url('users/user-roles')}}" style="width: 150px;">Role Manager</a></li>
        @endif
      	
        <?php $condition = in_array(22, $access_rights["admin"]) ; ?>
        @if($condition)
        <li>
      		<a href="{{url('users/staff-attendance')}}" style="width: 150px;">Staff Attendance</a>
      	</li>
        @endif
    </ul>
</li>
@endif


<?php $condition = in_array(17, $access_rights["admin"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'parameter') active @endif">
    <a href="{{url('/parameters')}}"><i class="icon-settings icons "></i> <span>Report Parameters</span></a>
</li>
@endif

<?php $condition = in_array(25, $access_rights["admin"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'groupType') active @endif">
    <a href="{{url('/group-type')}}"><i class="icon-organization icons "></i> <span>Drop Down Master</span></a>
</li>
@endif

<?php $condition = in_array(25, $access_rights["admin"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'holidays') active @endif">
    <a href="{{url('/holidays')}}"><i class="icon-people icons "></i> <span>Holidays</span></a>
</li>
@endif


<?php $condition = in_array(25, $access_rights["admin"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'settings') active @endif">
    <a href="{{url('/settings')}}"><i class="icon-settings icons "></i> <span>Settings</span></a>
</li>
@endif

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