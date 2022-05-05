<div class="text-center">
	<div style="background: #FFF; border-radius: 50%; height: 80px; width: 80px; display: inline-block; margin: 10px 0; overflow: hidden;">
		<!-- <img src="{{ url('assets/images/logo.png') }}" style="height: auto; width: 80px; margin-top: 18px;" /> -->
	</div>
</div>
<?php if(!isset($sidebar)) $sidebar = ""; ?>
<ul>
	@include("menus.".$menu)
</ul>