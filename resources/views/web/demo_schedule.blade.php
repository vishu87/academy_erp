@extends('layout_web')

@section('content')

	<x-web.container :background="$background" :logo="$logo_url" controller="Demo_controller" init="init()">
		<h2>{{$heading}}</h2>
		<p>{{ $description }}</p>
		<x-ngform name="addForm" ng-submit="onSubmit(addForm.$valid)">
			<div class="row">
				<div class="col-md-6 form-group">
					<x-input label="Name" name="formData.name" :required="true" />
				</div>
				<div class="col">
					<x-dob label="Date of Birth" name="formData" :required="true" />
				</div>
			</div>
			<div class="row">
				<div class="col">
					<x-input type="mobile" label="Mobile" name="formData.prim_mobile" :required="true" />
				</div>
				<div class="col">
					<x-input type="email" label="Email" name="formData.prim_email" :required="true" />
				</div>
			</div>
			<div class="row">
				<div class="col">
					<x-select label="Select City" name="formData.city_id" :required="true">
						<option value="">Select City</option>
						<option  ng-repeat="city in cities" value="@{{city.id}}">@{{city.city_name}}</option>
					</x-select>
				</div>
				<div class="col">
					<x-select label="Scholarship Type" name="formData.scholer_type">
						<option value="">Select</option>
						<option value="father">Father</option>
						<option value="mother">Mother</option>
						<option value="other">Self</option>
					</x-select>
				</div>
			</div>
			<div class="row">
				<div class="col">
				</div>
				<div class="col-md-12">
					<x-input type="textarea" label="Tell Us About Yourself" name="formData.discription" :required="true" />
				</div>
			</div>
			<div class="text-right mt-3">
				<x-web.button type="submit" class="block" loading="processing">Submit</x-web.button>
			</div>
		</x-ngform>
	</x-web.container>

@endsection


@section('footer_scripts')

@endsection