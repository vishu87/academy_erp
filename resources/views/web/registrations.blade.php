@extends('layout_web')

@section('content')

@if($payment_gateway == "razorpay")
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

<x-web.container :background="$background" :logo="$logo_url" controller="Reg_controller" init="init()">

	<h2>{{$heading}}</h2>
	<p>{{ $description }}</p>

	<div ng-show="tab == 1">
		@include("web.registration_form")
	</div>

	<div ng-show="tab == 2">
		@include("web.registration_checkout")
	</div>
	
</x-web.container>

@endsection