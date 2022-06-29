@extends('layout_web')

@section('content')

	<x-web.container :background="$background" :logo="$logo_url" controller="Demo_controller" init="init()" footer="">
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
					<x-input type="mobile" label="Mobile" name="formData.mobile" :required="true" />
				</div>
				<div class="col">
					<x-input type="email" label="Email" name="formData.email" :required="true" />
				</div>
			</div>
			<div class="row">
				<div class="col">
					<x-select label="Select Training City" name="formData.city_id" :required="true">
						<option value="">Select City </option>
						<option  ng-repeat="city in cities" value="@{{city.id}}">@{{city.city_name}}</option>
					</x-select>
				</div>

				<div class="col">
					<x-select label="Select Center for training" name="formData.center_id" :required="true">
						<option value="">Select Center</option>
						<option  ng-repeat="center in centers" value="@{{center.id}}" ng-if="formData.city_id == center.city_id">@{{center.center_name}}</option>
					</x-select>
				</div>

				<div class="col">
					<x-select label="Select Batch" name="formData.group_id" ng-change="schedule()" :required="true">
						<option value="">Select Batch</option>
						<option  ng-repeat="group in groups" value="@{{group.id}}" ng-if="formData.center_id == group.center_id">@{{group.group_name}}</option>
					</x-select>
				</div>
			</div>

			<div class="row" ng-if="formData.group_id">
				<div class="col">
					<x-select label="Select Visit Date" name="formData.visit_date"  :required="false">
						<option value="">Select Date</option>
						<option  ng-repeat="date in visit_dates" value="@{{date.value}}" >@{{date.label}}</option>
					</x-select>
				</div>
				<div class="col">
					<label>Visit Time </label><br>
					<label>@{{visit_time}}</label>
				</div>
			</div>

			<div class="text-right mt-3">
				<x-web.button type="submit" class="block" loading="processing">Submit</x-web.button>
			</div>
		</x-ngform>
	</x-web.container>

@endsection