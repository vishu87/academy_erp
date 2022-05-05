<li class="parent @if($sidebar == 'payments') active @endif ">
	<a href="{{url('/payments')}}">
		<i class="icon-calculator icons "></i>
		<span>Payments</span>
	</a>
</li>

<li class="parent @if($sidebar == 'p_categories') active @endif ">
	<a href="{{url('/pay-type-category')}}">
		<i class="icon-list icons "></i>
		<span>Categories</span>
	</a>
</li>

<li class="parent @if($sidebar == 'p_structure') active @endif ">
	<a href="{{url('pay-type-price')}}" >
		<i class="icon-organization icons "></i>
		<span>Structure</span>
	</a>
</li>

<li class="parent @if($sidebar == 'p_coupons') active @endif ">
	<a href="{{url('coupons')}}" >
		<i class="icon-wallet icons "></i>
		<span>Coupons</span>
	</a>
</li>

<li class="parent @if($sidebar == 'p_tax') active @endif ">
	<a href="{{url('/tax-settings')}}">
		<i class="icon-settings icons "></i>
		<span>Tax Settings</span>
	</a>
</li>