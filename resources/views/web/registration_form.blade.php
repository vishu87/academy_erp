<x-ngform name="addForm" ng-submit="onSubmit(addForm.$valid)">

	<x-input label="Name of the student" name="formData.name" :required="true" />

	<div class="row">
		<div class="col-md-6">
			<x-dob label="Date of Birth" name="formData" :required="true" />
		</div>
		<div class="col-md-3">
			@php
				$genders = ["1"=>"Male", "2"=>"Female"]
			@endphp
			<x-radio label="Gender" :options="$genders" name="formData.gender" :required="true" />
		</div>
		<div class="col-md-3">
			<x-select label="Kit Size" name="formData.kit_size" :required="true">
				<x-slot name="link">
			        <a href="" ng-click="showSizeChart()">Details</a>
			    </x-slot>
				<option value="">Choose size</option>
				<option value="26">26</option>
				<option value="28">28</option>
				<option value="30">30</option>
				<option value="32">32</option>
				<option value="34">34</option>
				<option value="36">36</option>
				<option value="38">38</option>
				<option value="40">40</option>
				<option value="42">42</option>
			</x-select>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<x-input label="Father's Name" name="formData.father_name" :required="true" />
		</div>
		<div class="col">
			<x-input label="Mother's Name" name="formData.mother_name" :required="true" />
		</div>
	</div>
		
	<div class="mt-2"></div>
	<b class="blue-text">Primary Contact</b>
	<div class="row">
		<div class="col">
			<x-input type="email" label="Email" name="formData.prim_email" :required="true" />
		</div>
		<div class="col">
			<x-input type="mobile" label="Mobile" name="formData.prim_mobile" :required="true" />
		</div>
		<div class="col">
			<label>Relation with student <span class="text-danger">*</span></label>
			<select class="form-control" ng-required="true" ng-model="formData.prim_relation_to_student" convert-to-number>
				<option value="">Select</option>
				<option value="1">Father</option>
				<option value="2">Mother</option>
				<option value="3">Self</option>	
			</select>
		</div>
	</div>
	<div class="mt-2"></div>
	<b class="blue-text">Secondary Contact (Optional)</b>
	<div class="row">
		<div class="col">
			<x-input type="email" label="Email" name="formData.sec_email" />
		</div>
		<div class="col">
			<x-input type="mobile" label="Mobile No." name="formData.sec_mobile" />
		</div>
		<div class="col">
			<div class="form-group">
				<label>Relation with student</label>
				<select class="form-control" ng-required="formData.sec_email || formData.sec_mobile" ng-model="formData.sec_relation_to_student" convert-to-number>
					<option value="">Select</option>
					<option value="1">Father</option>
					<option value="2">Mother</option>
					<option value="3">Self</option>	
				</select>
			</div>
		</div>
	</div>
	<div class="row">

		<div class="col-12">
			<x-input type="textarea" label="Full Valid Address ( for courier)" name="formData.address" :required="true" />
		</div>

		<div class="col-4">
			<x-select label="Address State" name="formData.address_state_id" :required="true" ng-change="getStateCity()">
				<option value="">Select State</option>
				<option ng-repeat="state in states" value="@{{state.id}}">@{{state.state_name}}</option>
			</x-select>
		</div>

		<div class="col-4">
			<x-select label="Address City" name="formData.address_city_id" :required="true">
				<option value="">Select City</option>
				<option  ng-repeat="city in state_cities" value="@{{city.value}}">@{{city.label}}</option>
			</x-select>
		</div>

		<div class="col-4">
			<x-input label="Pin Code" type="pin_code" name="formData.pin_code" :required="true" />
		</div>

	</div>
	<hr>
	<div class="row">

		<div class="col-12">
			<x-select label="Select Training City" name="formData.training_city_id" :required="true">
				<option value="">Select City </option>
				<option  ng-repeat="city in cities" value="@{{city.id}}">@{{city.city_name}}</option>
			</x-select>
		</div>

		<div class="col-12">
			<x-select label="Preferred Center for training" name="formData.training_center_id" :required="true">
				<option value="">Select Center</option>
				<option  ng-repeat="center in centers" value="@{{center.id}}" ng-if="formData.training_city_id == center.city_id">@{{center.center_name}}</option>
			</x-select>
		</div>

		<div class="col-12">
			<x-select label="Select Batch" name="formData.group_id" :required="true" ng-change="resetPayment()">
				<option value="">Select Batch</option>
				<option  ng-repeat="group in groups" value="@{{group.id}}" ng-if="formData.training_center_id == group.center_id">@{{group.group_name}}</option>
			</x-select>
		</div>

	</div>
	<hr />

	<div class="row" ng-if="formData.group_id">
		<div class="col-md-6" ng-repeat="item in payment_options">
			<div >
				<div class="form-group">
				    <label>@{{item.label}} <span ng-if="item.required" class="text-danger">*</span></label>
				    <select class="form-control" ng-model="item.type_id" ng-change="getPaymentItems()" ng-required="item.required">
				        <option value="">Select</option>
						<option ng-repeat="type in item.types" value="@{{type.value}}" >@{{type.label}}</option>
				    </select>
				</div>
			</div>
		</div>
	</div>

	<div ng-if="formData.group_id">
		<div class="" style="background: #F2F2F2">
			<table class="table">
				<thead>
					<tr>
						<th>Type</th>
						<th>Amount</th>
						<th>Tax</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in payment_items">
						<td>@{{ item.category }}</td>
						<td>
							@{{ item.taxable_amount | INR}}
							<span ng-if="item.discount" class="save-tag green">Saved Rs. @{{ item.discount }}</span>
						</td>
						<td>@{{ item.tax_perc }}%</td>
						<td>@{{ item.total_amount | INR}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="table-div full" ng-if="total_amount > 0">
		<div>
			<div ng-if="!coupon_code" >
				<div class="table-div apply-div" >
					<div class="input">
						<input type="text" ng-model="formData.coupon_code" class="form-control" placeholder="Discount code" />
					</div>
					<div class="pl-3">
						<button type="button" ng-click="checkCoupon()" class="btn btn-sm btn-info">Apply</button>
						
					</div>
				</div>
			</div>
			<div ng-if="coupon_code">
				<p class="mt-2 mb-1">
					Coupon applied 
				</p>
				<div class="coupon-code-box table-div">
					<span class="d-inline-block coupon">
						@{{ coupon_code }}
					</span>
					<button type="button" ng-click="removeCoupon()" class="remove-coupon">
						<i class="icon-close"></i>
					</button>
				</div>
				<p>
					<small>@{{ coupon_code_message }}</small>
				</p>
			</div>
		</div>
		<div class="text-center" style="font-size: 16px; width: 200px">
			Total Amount: <b>₹@{{ total_amount | INR}}</b>
		</div>
	</div>



	<div class="form-check" style="margin-top: 20px;">
	  <label class="form-check-label">
	  	<input class="form-check-input" ng-model="formData.confirm" type="checkbox" value="1" ng-required="true" ng-checked="false">
	    I hereby confirm that the information provided above is accurate and I agree to <a href="{{url('pages/terms-conditions')}}" target="_blank">terms and conditions</a>.
	  </label>
	</div>

	<div class="text-right mt-3">
		<x-web.button type="submit" class="block" loading="processing">Next</x-web.button>
	</div>

</x-ngform>

<x-modals id="kit_size" title="Kit Size Details" modal-size="modal-lg">
<div style="font-size: 12px; text-align: center;">
    <table style="width: 100%">
        <tr>
            <th>Size</th>
            <th>Age</th>
        </tr>
        <tr>
            <td>26</td>
            <td>4 to 5 years</td>
        </tr>
        <tr>
            <td>28</td>
            <td>5 to 8 years</td>
        </tr>
        <tr>
            <td>30</td>
            <td>8 to 10 years</td>
        </tr>
        <tr>
            <td>32</td>
            <td>10 to 11 years</td>
        </tr>
        <tr>
            <td>34</td>
            <td>11 to 12 years</td>
        </tr>
        <tr>
            <td>36</td>
            <td>12 to 13 years</td>
        </tr>
        <tr>
            <td>38</td>
            <td>13 to 14 years</td>
        </tr>
        <tr>
            <td>40</td>
            <td>14 to 15 years</td>
        </tr>
        <tr>
            <td>42</td>
            <td>15 to 16 years</td>
        </tr>
    </table>
    - The above-mentioned sizes tend to fit the corresponding age category.<br>
    - Kit can be collected from the head coach of that particular centre. (Only on the training days)<br>
    - Kit can be exchanged at centre with a new size if the chosen size doesn’t fit the student. (Only on the training days) 
</div>
</x-modals>