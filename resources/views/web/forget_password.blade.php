@extends('layout_web')

@section('content')


<x-web.container :background="$background" :logo="$logo_url" controller="SignUp_controller" init="">

	<h2>{{$heading}}</h2>
	<p>{{ $description }}</p>

	
	<div ng-if="tab == 1">
		<x-ngform name="addForm" ng-submit="forgetPassword(addForm.$valid)">
			<div class="row">
				<div class="col-md-12 ">
					<x-input type="text" label="Email" name="formData.email" :required="true" />
				</div>
			</div>
			
			<div class=" mt-3">
				<x-web.button type="submit" class="block" loading="processing">Reset Password</x-web.button> &nbsp;&nbsp;&nbsp;&nbsp; <a href="{{url('/')}}">Click here </a> to login to your account
			</div>

		</x-ngform>
	</div>

	<div ng-if="tab == 2">
		<x-web.success message="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua."></x-web.success>
	</div>
		
	
</x-web.container>

@endsection