@extends('layout_web')

@section('content')

@if($payment_gateway == "razorpay")
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script type="text/javascript">
		payment_code = "{{$payment_code}}";
	</script>
@endif

<x-web.container :background="$background" :logo="$logo_url" controller="Reg_controller" init="init()" :footer="$params->param_37">

	<h2>{{$heading}}</h2>
	<p>{{ $description }}</p>

	<div ng-show="tab == 1">
		@include("web.registration_form")
	</div>

	<div ng-show="tab == 2">
		@include("web.registration_checkout")
	</div>

	<div ng-show="tab == 3">
		@include("web.reg_completion")
	</div>
	
</x-web.container>

@endsection