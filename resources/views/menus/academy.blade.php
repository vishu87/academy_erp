<li class="parent @if($sidebar == 'students') active @endif">
	<a href="{{url('/students')}}">
		<i class="icon-people icons "></i>
		<span>Students</span>
	</a>
</li>

<li class="parent @if($sidebar == 'attendance') active @endif">
	<a href="{{url('/students/attendance')}}">
		<i class="icon-calendar icons "></i>
		<span>Attendance</span>
	</a>
</li>

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