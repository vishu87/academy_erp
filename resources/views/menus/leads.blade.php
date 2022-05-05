<li class="parent @if($sidebar == 'leads') active @endif">
	<a href="{{url('/leads')}}"><i class="icon-feed icons "></i> <span>Leads</span></a>
</li>

<li class="parent @if($sidebar == 'master-lead') active @endif">
	<a href="{{url('/master-leads')}}">
		<i class="icon-layers icons "></i>
		 <span>Master Leads</span>
	</a>
</li>