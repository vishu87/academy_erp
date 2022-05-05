@extends('layout')

@section('sub_header')
	<div class="sub-header">
		<div class="table-div full">
			<div>
				<h4 class="fs-18 bold" style="margin:0;">Add New Request</h4>
			</div>
			<div class="text-right">
				<a href="{{url('/inventory/request')}}"><i class="fa fa-angle-left"></i> Go Back</a>	
			</div>
		</div>
	</div>
@endsection

@section('content')

<div class="ng-cloak" ng-controller="Request_controller" ng-init="formData({{$id}})">

	<div class="portlet">

		<div class="portlet-body">
            
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Type</label>
                        <select class="form-control" ng-model="requestData.type" ng-disabled="requestData.id" convert-to-number>
                            <option>Select Type</option>
                            <option value="1">Purchase</option>
                            <option value="2">Transfer</option>
                            <option value="3">Consume</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 form-group">
                        <label>Date</label>
                        <input type="text" ng-model="requestData.date" class="form-control datepicker">
                    </div>
                </div>
				<div class="row">
                    <div class="col-sm-4 form-group" ng-if="requestData.type != 1">
                        <label>From City</label>
                        <select ng-model="requestData.out_city_id" class="form-control" ng-disabled="requestData.id" >
                            <option value="">Select</option>
                            <option ng-repeat="city in state_city_center.city" 
                            ng-value="@{{city.id}}">@{{city.city_name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-4 form-group" ng-if="requestData.type != 1">
                        <label>From Center</label>
                        <select ng-model="requestData.out_center_id" class="form-control" ng-disabled="requestData.id" >
                            <option value="">Select</option>
                            <option ng-repeat="center in state_city_center.center" 
                            ng-value="@{{center.id}}"  ng-if="center.city_id == requestData.out_city_id">@{{center.center_name}}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 form-group" ng-if="requestData.type != 3">
                        <label>To City</label>
                        <select ng-model="requestData.in_city_id" class="form-control" ng-disabled="requestData.id" >
                            <option value="">Select</option>
                            <option ng-repeat="city in state_city_center.city" 
                            ng-value="@{{city.id}}">@{{city.city_name}}
                            </option>
                        </select>
                    </div>

                    <div class="col-sm-4 form-group" ng-if="requestData.type != 3">
                        <label>To Center</label>
                        <select ng-model="requestData.in_center_id" class="form-control" ng-disabled="requestData.id" >
                            <option value="">Select</option>
                            <option ng-repeat="center in state_city_center.center" 
                            ng-value="@{{center.id}}"  ng-if="center.city_id == requestData.in_city_id">@{{center.center_name}}
                            </option>
                        </select>
                    </div>
				</div>

                <div class="row">
                    <div class="col-md-4 form-group" ng-if="requestData.type == 1">
                        <label>Company</label>
                        <select ng-model="requestData.company_id" class="form-control"  convert-to-number>
                            <option value="">Select</option>
                            <option value="@{{company.id}}" ng-repeat="company in companies">@{{company.company_name}}</option>
                        </select>
                    </div> 

                    <div class="col-md-4 form-group" ng-if="requestData.type == 1">
                        <label>Invoice Number</label>
                        <input type="text" ng-model="requestData.invoice_number" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Document</label>
				        <button type ="button" class ="form-control" ng-if =" fileObj.document == '' " ngf-select ="uploadDocument($file)" >Choose Document</button>
				        <div ng-if ="fileObj.document != '' ">
                            <div ng-if="requestData.id" >
                                <a ng-href="{{ url('/') }}/@{{fileObj.document}}"  target="_blank" class="btn-sm btn btn-primary">View</a>
                            </div>
                            <div ng-if="!requestData.id" >
                                <a ng-href="@{{fileObj.link}}"  target="_blank" class="btn-sm btn btn-primary">View</a>
                            </div>
				              <button type ="button" class="btn btn-sm btn-danger" ng-click="removeFile()" >Remove</button>
				        </div>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Remark</label>
                        <textarea ng-model="requestData.remark" class="form-control" rows="4" cols="50"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="bold">Item</label>
                    </div>
                    <div class="col-md-4">
                        <label class="bold">Quantity</label>
                    </div>
                </div>

                <div class="row form-group" ng-repeat="item in items track by $index">
                	<div class="col-md-4">
                        <select class="form-control" ng-model="item.item_id" convert-to-number>
                            <option ng-repeat="item in allItems" value="@{{item.id}}">@{{item.item_name}}</option>
                        </select>
                	</div>
                	<div class="col-md-4">
                		<input type="text" ng-model="item.quantity" class="form-control" >
                	</div>
                	<div class="col-md-4">
                		<button type="button" class="btn btn-danger" ng-click="removeItem($index)">Remove</button>
                	</div>
                </div>
                <button type="button" ng-click="addItem()" class="btn btn-primary">Add Items</button>
                <hr>
                <button type="button" ng-click="saveRequest()" class="btn btn-success ">Save Request</button>

		</div>
	</div>
</div>
@endsection

@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/Request_controller.js?v='.env('JS_VERSION')) }}" ></script>

@endsection