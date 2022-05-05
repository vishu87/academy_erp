<li class="parent @if($sidebar == 'request') active @endif">
	<a href="{{url('/inventory/request')}}">
		<i class="icon-loop icons "></i>
		<span>Request</span>
	</a>
</li>

<li class="parent @if($sidebar == 'stock') active @endif">
	<a href="{{url('/inventory/current-stock')}}">
		<i class="icon-layers icons "></i>
		<span>Current Stock</span>
	</a>
</li>

<li class="parent @if($sidebar == 'report') active @endif" >
	<a  href="{{url('/inventory/inventory-report')}}">
		<i class="icon-check icons "></i>
		<span>Report</span>
	</a>
</li>

<li class="parent @if($sidebar == 'companies') active @endif" >
	<a  href="{{url('/inventory/companies')}}">
		<i class="icon-organization icons "></i>
		<span>Companies</span>
	</a>
</li>

<li class="parent @if($sidebar == 'item') active @endif" >
	<a  href="{{url('/inventory/item')}}">
		<i class="icon-badge icons "></i>
		<span>Item</span>
	</a>
</li>