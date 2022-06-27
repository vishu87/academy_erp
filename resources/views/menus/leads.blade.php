<?php $access_rights = Session::get("access_rights"); ?>

<?php $condition = in_array(12, $access_rights["leads"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'leads') active @endif">
	<a href="{{url('/leads')}}"><i class="icon-feed icons "></i> <span>Leads</span></a>
</li>
@endif

<?php $condition = in_array(11, $access_rights["leads"]) ; ?>
@if($condition)
<li class="parent @if($sidebar == 'master-lead') active @endif">
	<a href="{{url('/master-leads')}}">
		<i class="icon-layers icons "></i>
		 <span>Lead Variables</span>
	</a>
</li>
@endif