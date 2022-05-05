<x-ngform name="addForm" ng-submit="onSubmit(addForm.$valid)">

<x-input label="Name of the student" name="formData.name" :required="true" />

<div class="row">
	<div class="col">
		<x-dob label="Date of Birth" name="formData" :required="true" />
	</div>
	<div class="col">
		@php
			$genders = ["1"=>"Male", "2"=>"Female"]
		@endphp
		<x-radio label="Date of Birth" :options="$genders" name="formData.gender" :required="true" />
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
		<x-select label="Relation with student" name="formData.prim_relation_to_student" :required="true">
			<option value="">Select</option>
			<option value="father">Father</option>
			<option value="mother">Mother</option>
			<option value="other">Self</option>
		</x-select>
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
		<x-select label="Relation with student" name="formData.sec_relation_to_student">
			<option value="">Select</option>
			<option value="father">Father</option>
			<option value="mother">Mother</option>
			<option value="other">Self</option>
		</x-select>
	</div>
</div>
<div class="row">

	<div class="col-12">
		<x-input type="textarea" label="Full Valid Address ( for courier)" name="formData.address" :required="true" />
	</div>

	<div class="col-4">
		<x-select label="State" name="formData.state_id" :required="true">
			<option value="">Select State</option>
			<option ng-repeat="state in states" value="@{{state.id}}">@{{state.state_name}}</option>
		</x-select>
	</div>

	<div class="col-4">
		<x-select label="City" name="formData.city_id" :required="true">
			<option value="">Select City</option>
			<option  ng-repeat="city in cities" value="@{{city.id}}">@{{city.city_name}}</option>
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
		<x-select label="Select Batch" name="formData.group_id" :required="true">
			<option value="">Select Batch</option>
			<option  ng-repeat="group in groups" value="@{{group.id}}" ng-if="formData.training_center_id == group.center_id">@{{group.group_name}}</option>
		</x-select>
	</div>

</div>

<div class="row">
	<div class="col-12">
		<x-select label="Kit Size" name="formData.kit_size" :required="true">
			<x-slot name="link">
		        <a href="" ng-click="showSizeChart()">View Details</a>
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
<hr />
<div class="row">
	<div class="col-md-6">
		<div ng-repeat="item in payment_options">
			<div class="form-group">
			    <label>@{{item.label}} <span ng-if="item.required" class="text-danger">*</span></label>
			    <select class="form-control" ng-model="item.type_id" ng-change="getPaymentItems()">
			        <option value="">Select</option>
					<option ng-repeat="type in item.types" value="@{{type.value}}" >@{{type.label}}</option>
			    </select>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="" style="background: #EEE">
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
						<td>@{{ item.amount }}</td>
						<td>@{{ item.tax_perc }}%</td>
						<td>@{{ item.total_amount }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>



<div class="form-check">
  <label class="form-check-label">
  	<input class="form-check-input" type="checkbox" value="1" required="">
    I hereby confirm that the information provided above is accurate and I agree to <a href="tnc.php" target="_blank">terms and conditions</a>.
  </label>
</div>

<x-button type="submit" class="block" spin="processing">Submit Details</x-button>

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