@extends('layout_web')

@section('content')
<x-web.container :background="$background" :logo="$logo_url" controller="Reg_controller" init="init()">

	<h2>{{$heading}}</h2>
	<p>{{ $description }}</p>

	<div ng-show="tab == 1">
		@include("web.registration_form")
	</div>
	
</x-web.container>

@endsection