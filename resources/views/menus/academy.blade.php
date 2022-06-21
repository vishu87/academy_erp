<?php $access_rights = Session::get("access_rights"); ?>

<?php $condition = in_array(1, $access_rights["academy"]) || in_array(2, $access_rights["academy"]); ?>
@if($condition)
<li class="parent @if($sidebar == 'students') active @endif">
	<a href="{{url('/students')}}">
		<i class="icon-people icons "></i>
		<span>Students</span>
	</a>
</li>
@endif

<?php $condition = in_array(13, $access_rights["academy"]); ?>
@if($condition)
<li class="parent @if($sidebar == 'attendance') active @endif">
	<a href="{{url('/students/attendance')}}">
		<i class="icon-calendar icons "></i>
		<span>Attendance</span>
	</a>
</li>
@endif

<?php $condition = in_array(14, $access_rights["academy"]); ?>
@if($condition)
<li class="parent @if($sidebar == 'performance') active @endif" >
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
@endif