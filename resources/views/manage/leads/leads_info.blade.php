<div ng-show="!edit_lead" class="mt-3">
    
    <div class="alert alert-info">
        @{{leadData.status_value}} - @{{leadData.assigned_member}} <span ng-if="leadData.action_date">@ @{{ leadData.action_date | date }}</span>
    </div>

    <div class="row">
        <div class="static-info col-md-4">
          <span><i class="icons icon-people"></i> Name</span> @{{ leadData.name }}
        </div>

        <div class="static-info col-md-4">
          <span><i class="icons icon-calendar"></i> DOB</span> @{{ leadData.dob }}
        </div>

        <div class="static-info col-md-4">
          <span><i class="icons icon-phone"></i> Mobile</span> @{{ leadData.mobile }}
        </div>

        <div class="static-info col-md-12">
          <span><i class="icons icon-location-pin"></i> Training details</span> <small>Group -</small> @{{ leadData.group_name }} &nbsp;&nbsp;&nbsp; <small>Center-</small> @{{ leadData.center_name }}
        </div>

        <div class="static-info col-md-12">
          <span><i class="icons icon-map"></i> Address</span>@{{leadData.client_address}} &nbsp;&nbsp; <small>City-</small> @{{leadData.client_city_name}} &nbsp;&nbsp; <small>State-</small> @{{leadData.client_state_name}} 
        </div>

        
        <div class="static-info col-md-6">
          <span><i class="icons icon-layers"></i> Source</span> @{{leadData.source ? leadData.source : "NA"}} 
        </div>

        <div class="static-info col-md-6">
          <span><i class="icons icon-docs"></i> Lead For</span> @{{ leadData.lead_for }} 
        </div>

        <div class="static-info col-md-12">
          <span><i class="icons icon-home"></i> School Name</span> @{{ leadData.school_name ? leadData.school_name : "NA" }} 
        </div>

        <div class="static-info col-md-12">
          <span><i class="icons icon-book-open"></i> Class Studying</span> @{{ leadData.class_studying ? leadData.class_studying : "NA" }} 
        </div>

        <div class="static-info col-md-12">
          <span><i class="icons icon-note"></i> Remarks</span> @{{ leadData.remarks ? leadData.remarks : "NA" }} 
        </div>

    </div>

</div>

<form ng-submit="onSubmitLead(leadForm.$valid)" novalidate name="leadForm" ng-show="edit_lead"> 

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Name <span class="required">*</span></label>
            <input type="text" ng-model="leadData.name" class="form-control" required>
        </div>
        <div class="col-md-6 form-group">
            <label>DOB</label>
            <input type="text" ng-model="leadData.dob" class="form-control datepicker">
        </div>
        <div class="col-md-6 form-group ">
            <label>Gender</label><br>
            <label>
                <input type="radio" convert-to-number ng-model="leadData.gender" value="1" > &nbsp;Male 
            </label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label>
                <input type="radio" convert-to-number ng-model="leadData.gender" value="2" > &nbsp;Female
            </label>
        </div>
        <div class="col-md-6 form-group">
            <label>Mobile <span class="required">*</span></label>
            <input type="text" ng-model="leadData.mobile" class="form-control" required>
        </div>
        <div class="col-md-6 form-group">
            <label>Email</label>
            <input type="text" ng-model="leadData.client_email" class="form-control">
        </div>

        <div class="col-md-6 form-group">
            <label>Adresss </label>
            <input type="text" ng-model="leadData.client_address" class="form-control">
        </div>

        <div class="col-md-6">
            <label>Address State</label>
            <select class="form-control" ng-model="leadData.client_state_id">
                <option >Select</option>
                <option ng-repeat="state in states" ng-value="state.id">@{{state.state_name}}</option>
            </select>
        </div>

        <div class="col-md-6">      
            <label>Address City</label>
            <select class="form-control" ng-model="leadData.client_city_id" ng-change="getClientState()">
                <option >Select</option>
                <option ng-repeat="city in all_cities" ng-value="city.value">@{{city.label}}</option>
            </select>
        </div>
        

        <div class="col-md-6 mt-2 form-group">
            <label>Lead For</label>
            <select ng-model="leadData.lead_for" class="form-control" convert-to-number>
                <option value="">Select</option>
                <option value="@{{product.value}}" ng-repeat="product in parameters.lead_for">@{{product.label}}</option>
            </select>
        </div>

        <div class="col-md-6 mt-2 form-group">
            <label>Lead Source <span class="required">*</span></label>
            <select class="form-control" ng-model="leadData.lead_source">
                <option ng-repeat="source in parameters.lead_sources" ng-value="source.value">@{{source.label}}</option>
            </select>
        </div>
        
        <div class="col-md-6 form-group">
            <label>Training City</label>
            <select class="form-control" ng-model="leadData.city_id">
                <option >Select</option>
                <option ng-repeat="city in parameters.city" ng-value="city.value">@{{city.label}}</option>
            </select>
        </div>
        
        <div class="col-md-6 form-group" ng-show="leadData.city_id > 0">
            <label>Center</label>
            <select class="form-control" ng-model="leadData.center_id">
                <option >Select</option>
                <option ng-repeat="center in parameters.center" ng-value="center.value" ng-if="center.city_id == leadData.city_id">@{{center.label}}</option>
            </select>
        </div>

        <div class="col-md-6 form-group" ng-show="leadData.city_id > 0">
            <label>Age Group </label>
            <select class="form-control" ng-model="leadData.group_id">
                <option>Select</option>
                <option ng-repeat="age_group in parameters.group" ng-value="age_group.value" ng-if="age_group.center_id == leadData.center_id">@{{age_group.label}}</option>
            </select>
        </div>

        <div class="col-md-12 form-group">
            <label>Remarks</label>
            <textarea class="form-control" ng-model="leadData.remarks"></textarea>
        </div>
        
        <div class="col-md-12">
            <button class="btn btn-primary" type="submit" ng-disabled="processing_req">Update <span ng-show="processing_req" class="spinner-border spinner-border-sm"></button>
        </div>
    </div>
</form>