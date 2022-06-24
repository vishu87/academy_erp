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
            <form name="InvForm" novalidate="novalidate" ng-submit="saveRequest(InvForm.$valid)">
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Type</label>
                            <select class="form-control" ng-model="requestData.type" convert-to-number required="true">
                                <option>Select Type</option>
                                <option value="1">Purchase</option>
                                <option value="2">Transfer</option>
                                <option value="3">Consume</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label>Date</label>
                            <input type="text" ng-model="requestData.date" class="form-control datepicker" required="true">
                        </div>
                    </div>
    				<div class="row" ng-if="requestData.type != 1">
                        <div class="col-md-6 form-group" >
                            <label>From City</label>
                            <select ng-model="requestData.out_city_id" class="form-control" required="true">
                                <option value="">Select</option>
                                <option ng-repeat="city in state_city_center.city" 
                                ng-value="@{{city.value}}">@{{city.label}}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>From Center</label>
                            <select ng-model="requestData.out_center_id" class="form-control"  required="true">
                                <option value="">Select</option>
                                <option ng-repeat="center in state_city_center.center" 
                                ng-value="@{{center.value}}"  ng-if="center.city_id == requestData.out_city_id">@{{center.label}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row" ng-if="requestData.type != 3">
                        <div class="col-md-6 form-group" >
                            <label>To City</label>
                            <select ng-model="requestData.in_city_id" class="form-control" required="true">
                                <option value="">Select</option>
                                <option ng-repeat="city in state_city_center.city" 
                                ng-value="@{{city.value}}">@{{city.label}}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>To Center</label>
                            <select ng-model="requestData.in_center_id" class="form-control" required="true">
                                <option value="">Select</option>
                                <option ng-repeat="center in state_city_center.center" 
                                ng-value="@{{center.value}}"  ng-if="center.city_id == requestData.in_city_id">@{{center.label}}
                                </option>
                            </select>
                        </div>
    				</div>
                    <div class="row">
                        <div class="col-md-6 form-group" ng-if="requestData.type == 1">
                            <label>Company</label>
                            <select ng-model="requestData.company_id" class="form-control"  convert-to-number required="true">
                                <option value="">Select</option>
                                <option value="@{{company.id}}" ng-repeat="company in companies">@{{company.company_name}}</option>
                            </select>
                        </div> 

                        <div class="col-md-6 form-group" ng-if="requestData.type == 1">
                            <label>Invoice Number</label>
                            <input type="text" ng-model="requestData.invoice_number" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Document</label>
    				        <button type ="button" class ="form-control" ng-if ="!requestData.document" ngf-select ="uploadDocument($file)" >Choose Document</button>
    				        <div ng-if ="requestData.document">
                                <div ng-if="requestData.id" >
                                    <a ng-href="{{ url('/') }}/@{{requestData.document}}"  target="_blank" class="btn-sm btn-primary btn">View</a>
                                </div>
                                <div ng-if="!requestData.id" >
                                    <a ng-href="@{{requestData.link}}"  target="_blank" class="btn-sm btn btn-primary">View</a>
                                </div>
    				              <button type ="button" class="btn btn-sm btn-danger" ng-click="removeFile()" >Remove</button>
    				        </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Remark</label>
                            <textarea ng-model="requestData.remark" class="form-control" rows="4" cols="50"></textarea>
                        </div>
                    </div>
                    
                </div>

                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-3">
                            <label class="bold">Item</label>
                        </div>
                        <div class="col-md-3">
                            <label class="bold">Quantity</label>
                        </div>
                        <div class="col-md-3">
                            <label class="bold">Price</label>
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                    <div class="row form-group" ng-repeat="item in requestData.items track by $index">
                        <div class="col-md-3">
                            <select class="form-control" ng-model="item.item_id" convert-to-number>
                                <option ng-repeat="item in allItems" value="@{{item.id}}">@{{item.item_name}}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" ng-model="item.quantity" class="form-control" >
                        </div>
                        <div class="col-md-3">
                            <input type="text" ng-model="item.price" class="form-control" >
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-danger" ng-click="removeItem($index)">Remove</button>
                        </div>
                    </div>
                    <button type="button" ng-click="addItem()" class="btn btn-primary"><i class="icons icon-plus"></i> Add Items</button>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary ">Save Request</button>
            </form>
		</div>
	</div>

</div>
@endsection

@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/Request_controller.js?v='.env('JS_VERSION')) }}" ></script>

@endsection