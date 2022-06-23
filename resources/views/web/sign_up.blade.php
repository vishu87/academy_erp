@extends('layout_web')

@section('content')


<x-web.container :background="$background" :logo="$logo_url" controller="SignUp_controller" init="init()">

	<h2>{{$heading}}</h2>
	<p>{{ $description }}</p>

	
	<div ng-if="tab == 1">
		<x-ngform name="addForm" ng-submit="onSubmit(addForm.$valid)">
			<div class="row">
				<div class="col-md-12 ">
					<x-input type="email" label="Email" name="formData.email" :required="true" />
				</div>
			</div>
			
			<div class=" mt-3">
				<x-web.button type="submit" class="block" loading="processing">Submit</x-web.button>
			</div>

		</x-ngform>
	</div>

	<div ng-show="tab == 2">
		<x-web.success message=""></x-web.success>
	</div>
		
	
</x-web.container>

@endsection