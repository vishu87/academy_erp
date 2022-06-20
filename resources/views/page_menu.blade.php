<div class="text-center">
	<div style="background:rgba(0,0,0,0.8); border-radius: 50%; height: 80px; width: 80px; display: inline-block; margin: 10px 0; overflow: hidden;display: flex;align-items: center;justify-content: center;">
		<img src="{{ url('assets/images/logo2x.png') }}" style="height:45px; width:auto;" />
	</div>
</div>
<?php if(!isset($sidebar)) $sidebar = ""; ?>
<ul>
	@include("menus.".$menu)
</ul>