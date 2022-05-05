@extends('layout')

@section('content')
	<div class="container-fluid" >
		
		<div ng-controller="events_controller" ng-init="init({{$id}})" style="margin-top: 10px"> 
			<div class="row">
				<div class="col-md-8">
					<h2 class="page-title">Parent App Events</h2>
				</div>
				<div class="col-md-4 text-right">
					<a href="parent_app.php?type=events" class="btn btn-primary">Go Back</a>
				</div>
			</div>
			<div ng-show="!loading" class="ng-cloak">
				
				<form ng-submit="addEvent(eventForm.$valid)" name="eventForm" novalidate>
					
					<div class="row">
						<div class="col-md-6 form-group">
							<label>Name <span class="error"> *</span></label>
							<input type="text" ng-model="formData.name" class="form-control" required>
						</div>
						<div class="col-md-3 form-group">
							<label>Start Date <span class="error"> *</span></label>
							<input type="text" ng-model="formData.start_date" class="form-control datepicker" autocomplete="off" required>
						</div>
						<div class="col-md-3 form-group">
							<label>End Date <span class="error"> *</span></label>
							<input type="text" ng-model="formData.end_date" class="form-control datepicker" autocomplete="off" required>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3 form-group">
							<label>Latitude <span class="error"> *</span></label>
							<input type="text" ng-model="formData.latitude" class="form-control" required>
						</div>
						<div class="col-md-3 form-group">
							<label>Longitude <span class="error"> *</span></label>
							<input type="text" ng-model="formData.longitude" class="form-control" required>
						</div>
						<div class="col-md-3 form-group">
							<label>Location <span class="error"> *</span></label>
							<select ng-model="formData.location_id" class="form-control" required>
								<option>Select</option>
								<option ng-repeat="city in cities" ng-value="city.id">@{{city.city_name}}</option>
							</select>
						</div>
						<div class="col-md-3 form-group">
							<label>Position <span class="error"> *</span></label>
							<input type="text" ng-model="formData.position" required class="form-control" autocomplete="off">
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-3 form-group hidden">
							<label>Pay later</label><br>
							<label>
								
								<input type="radio" ng-model="formData.pay_later" ng-value="1"> &nbsp;Yes
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="radio" ng-model="formData.pay_later" ng-value="0">&nbsp; No
							</label>
						</div>
						<div class="col-md-3 form-group">
							<label>Hidden (will not show in app)</label><br>
							<label>
								
								<input type="radio" ng-model="formData.hidden" ng-value="1">&nbsp; Yes
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="radio" ng-model="formData.hidden" ng-value="0">&nbsp; No
							</label>
						</div>
						<div class="col-md-3 form-group">
							<label>Registrations Closed</label><br>
							<label>
								
								<input type="radio" ng-model="formData.registration_closed" ng-value="1">&nbsp; Yes
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="radio" ng-model="formData.registration_closed" ng-value="0">&nbsp; No
							</label>
						</div>

						<div class="col-md-3 form-group">
							<label>Allowed Genders</label><br>
							<label>
								
								<input type="checkbox" ng-model="formData.allowed_genders[1]" ng-value="1">&nbsp; Male
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="checkbox" ng-model="formData.allowed_genders[2]" ng-value="2">&nbsp; Female
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="checkbox" ng-model="formData.allowed_genders[3]" ng-value="3">&nbsp; Other
							</label>
						</div>
						<div class="col-md-3 form-group">
							<label>Allowed Date of Birth</label>
							<div class="row">
								<div class="col-md-6">
									<input type="text" class="form-control datepicker" autocomplete="off" placeholder="Min DOB" ng-model="formData.min_dob">
								</div>
								<div class="col-md-6">
									<input type="text" class="form-control datepicker" autocomplete="off" placeholder="Max DOB" ng-model="formData.max_dob">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 form-group">
							<label>School Name Required?</label><br>
							<label>
								
								<input type="radio" ng-model="formData.school_name" ng-value="1">&nbsp; Yes
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="radio" ng-model="formData.school_name" ng-value="0">&nbsp; No
							</label>
						</div>
						<div class="col-md-3 form-group">
							<label>Kit Size Required?</label><br>
							<label>
								
								<input type="radio" ng-model="formData.kit_size" ng-value="1">&nbsp; Yes
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="radio" ng-model="formData.kit_size" ng-value="0">&nbsp; No
							</label>
						</div>
						<div class="col-md-3 form-group">
							<label>Allowed Offline Payment (if any)</label><br>
							<label>
								<input type="checkbox"  ng-model="formData.allowed_offline_payments[1]" ng-value="1">&nbsp; Cash
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="checkbox"  ng-model="formData.allowed_offline_payments[2]" ng-value="2">&nbsp; Cheque
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="checkbox"  ng-model="formData.allowed_offline_payments[3]" ng-value="3">&nbsp; DD
							</label>
						</div>
						<div class="col-md-3 form-group">
							<label>Website Banner Event?</label><br>
							<label>
								<input type="radio" ng-model="formData.web_banner" ng-value="1">&nbsp; Yes
							</label>
							&nbsp;&nbsp;&nbsp;
							<label>
								
								<input type="radio" ng-model="formData.web_banner" ng-value="0">&nbsp; No
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Meta Title</label>
								<input type="text" class="form-control" ng-model="formData.meta_title">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Meta Description</label>
								<input type="text" class="form-control" ng-model="formData.meta_description">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Meta Keywords</label>
								<input type="text" class="form-control" ng-model="formData.meta_keywords">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Slug</label>
								<input type="text" class="form-control" ng-model="formData.slug">
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-3 form-group">
							<div class="form-group">
								<label>Coupon Code</label>
								<input type="text" class="form-control" ng-model="formData.coupon_code">
							</div>
						</div>
						<div class="col-md-3 form-group">
							<div class="form-group">
								<label>Coupon Discount (%)</label>
								<input type="text" class="form-control" ng-model="formData.coupon_perc">
							</div>
						</div>
					</div>
					<hr>
					<h3 class="page-title">Payment Categories</h3>
					<div class="row">
						<div class="col-md-3"><br>
							<label>
								<input type="checkbox" ng-model="formData.payment_category[1]" ng-value="1">&nbsp;&nbsp;Present BBFS Player
							</label>
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[1]">
							<label>Amount <span class="error"> *</span></label>
							<input type="text" ng-model="formData.amount_bbfs"  ng-required="formData.payment_category[1]" class="form-control">
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[1]">
							<label>Tax(%) <span class="error"> *</span></label>
							<input type="text" ng-model="formData.tax_bbfs" ng-required="formData.payment_category[1]" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-md-3"><br>
							<label>
								<input type="checkbox" ng-model="formData.payment_category[2]" ng-value="1">&nbsp;&nbsp;Past BBFS Player
							</label>
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[2]">
							<label>Amount <span class="error"> *</span></label>
							<input type="text" ng-model="formData.amount_inactive"  ng-required="formData.payment_category[2]" class="form-control">
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[2]">
							<label>Tax(%) <span class="error"> *</span></label>
							<input type="text" ng-model="formData.tax_inactive" ng-required="formData.payment_category[2]" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-md-3"><br>
							<label>
								<input type="checkbox" ng-model="formData.payment_category[3]" ng-value="1">&nbsp;&nbsp;Played in a previous version of this event
							</label>
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[3]">
							<label>Amount <span class="error"> *</span></label>
							<input type="text" ng-model="formData.amount_pbbfs"  ng-required="formData.payment_category[3]" class="form-control">
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[3]">
							<label>Tax(%) <span class="error"> *</span></label>
							<input type="text" ng-model="formData.tax_pbbfs" ng-required="formData.payment_category[3]" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-md-3"><br>
							<label>
								<input type="checkbox" ng-model="formData.payment_category[4]" ng-value="1">&nbsp;&nbsp;Never has the BBFS experience
							</label>
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[4]">
							<label>Amount <span class="error"> *</span></label>
							<input type="text" ng-model="formData.amount"  ng-required="formData.payment_category[4]" class="form-control">
						</div>
						<div class="col-md-2 form-group" ng-if="formData.payment_category[4]">
							<label>Tax(%) <span class="error"> *</span></label>
							<input type="text" ng-model="formData.tax" ng-required="formData.payment_category[4]" class="form-control">
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-4 form-group">
							<label>Event Type <span class="error">*</span></label>
							<select convert-to-number class="form-control" ng-model="formData.event_type" required>
								<option value="">Select</option>
								<option value="1">Tournaments & Events</option>
								<option value="2">Tours</option>
								<option value="3">Camps</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Description <span class="error"> *</span></label>
						<input type="text" class="form-control" required ng-model="formData.description">
					</div>
					<div class="form-group">
						<label>Address <span class="error"> *</span></label>
						<input type="text" class="form-control" required ng-model="formData.address">
					</div>
					<div class="form-group">
						<label>Additional Remarks</label>
						<input type="text" class="form-control" ng-model="formData.additional_remarks">
					</div>
					<div class="form-group">
						<label>Youtube Link</label>
						<input type="text" class="form-control" ng-model="formData.video_link">
					</div>
					<div class="row">
						<div class="col-md-4">
							<label>App Banner (375x200)</label><br>
							<button type="button" ng-show="!formData.image" class="button btn btn-primary upload-btn" ngf-select="uploadFile($file,'image',formData)" ng-hide="image_uploading" data-style="expand-right" >Select Image</button>

							<a ng-href="@{{formData.image}}" ng-show="formData.image" class="btn btn-primary" target="_blank"> View Image</a>
							<button ng-show="formData.image" type="button" class="btn btn-danger" ng-click="removeFile('image')"> X</a>

						</div>

						<div class="col-md-4">
							<label>Web Banner (1215 x 391)</label><br>
							<button type="button" ng-show="!formData.web_image" class="button btn btn-primary upload-btn" ngf-select="uploadFile($file,'web_image',formData)" ng-hide="web_image_uploading" data-style="expand-right" >Select Image</button>

							<a ng-href="@{{formData.web_image}}" ng-show="formData.web_image" class="btn btn-primary" target="_blank"> View Image</a>
							<button ng-show="formData.web_image" type="button" class="btn btn-danger" ng-click="removeFile('web_image')"> X</a>

						</div>

						<div class="col-md-4">
							<label>Logo (230 x 182)</label><br>
							<button type="button" ng-show="!formData.logo" class="button btn btn-primary upload-btn" ngf-select="uploadFile($file,'logo',formData)" ng-hide="logo_uploading" data-style="expand-right" >Select Logo</button>

							<a ng-href="@{{formData.logo}}" ng-show="formData.logo" class="btn btn-primary" target="_blank"> View logo</a>
							<button ng-show="formData.logo" type="button" class="btn btn-danger" ng-click="removeFile('logo')"> X</a>
						</div>

					</div>
					<hr>
					<h3>Gallery</h3>
					<div class="row">
						<div class="col-md-3">
							<button type="button" class="button btn btn-primary upload-btn" ngf-select="uploadGalaryFile($files,'photo')" ladda="uploading" multiple data-style="expand-right" >Select Photos</button>
						</div>
					</div>
					<div class="row" style="margin-top: 10px">
						<div class="col-md-3" ng-repeat="gallery in formData.gallery">
							<div style="position: relative;">
								<img src="@{{gallery.media_thumb}}" style="width: 100%;height: auto;">
								<button style="position: absolute;top:5px;left: 10px" type="button" ng-click="removeGalleryImage($index)" class="btn btn-xs btn-danger">X</button>
							</div>
						</div>
					</div>
					
					<div>
						<button class="btn btn-primary" ladda="processing" type="submit" style="margin-top: 23px">@{{formData.id ? 'Update':'Add'}}</button>
					</div>
				</form>
			</div>
			<div ng-show="loading">
				Loading ...
			</div>

		</div>
	</div>

@endsection

@section('footer_scripts')
<script type="text/javascript" 
src="{{url('assets/plugins/admin/scripts/core/events/events_controller.js?v='.env('JS_VERSION'))}}"></script>
@endsection