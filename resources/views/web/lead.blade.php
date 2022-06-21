@extends('layout_web')

@section('content')
<x-web.container :background="$background" :logo="$logo_url" controller="Lead_controller" init="init()">
	
	<h2>{{$heading}}</h2>
	<p>{{ $description }}</p>
	
	<div ng-if="tab == 1">
		<x-ngform name="addForm" ng-submit="onSubmit(addForm.$valid)">
			<div class="row">
				<div class="col-md-6 ">
					<x-input type="text" label="Name" name="formData.name" :required="true" />
				</div>
				<div class="col-md-6">
					<x-dob label="Date of Birth" name="formData" :required="true" year="2000" />
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<x-input type="mobile" label="Mobile" name="formData.mobile" :required="true" />
				</div>
				<div class="col-md-6">
					<x-input type="email" label="Email" name="formData.email" :required="true" />
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<x-select label="City" :required="true" name="formData.city_id">
						<option value="">Select City</option>
						<option  ng-repeat="city in cities" value="@{{city.id}}">@{{city.city_name}}</option>
					</x-select>
				</div>
				<div class="col-md-6">
					<x-select label="Scholarship Type" :required="true" name="formData.scholor_type">
						<option value="">Select</option>
						<option value="Full">Full</option>
						<option value="Partial">Partial</option>
					</x-select>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<x-web.fileupload label="Upload Resume (JPEG,PNG,PDF)" name="formData.document_url" />
				</div>
				<div class="col-md-6">
					<x-input type="textarea" label="Tell Us About Yourself" name="formData.remarks" rows="3" />
				</div>
			</div>

			<div class="text-right mt-3">
				<x-web.button type="submit" class="block" loading="processing">Submit Details</x-web.button>
			</div>

		</x-ngform>
	</div>

	<div ng-if="tab == 2">
		<x-web.success message="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua."></x-web.success>
	</div>

</x-web.container>
@endsection


@section('footer_scripts')

@endsection