<div class="modal fade in" id="request-view-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Inventory Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body small-form" >
                <div class="portlet-body">
                    
                    <div class=" mt-1">

                        <div class="table-responsive">
                            <table class="table table-bordered">

                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <td ng-if="viewData.type == 1">Purchase</td>
                                        <td ng-if="viewData.type == 2">Transfer</td>
                                        <td ng-if="viewData.type == 3">Consume</td>

                                        <th>Date</th>
                                        <td>@{{viewData.date}}</td>

                                    </tr>

                                    <tr ng-if="viewData.fromCityName != null">
                                        <th>From City</th>    
                                        <td>@{{viewData.fromCityName}}</td>

                                        <th>From Center</th>  
                                        <td>@{{viewData.fromCenterName}}</td>
                                    </tr>

                                    <tr ng-if="viewData.toCityName != null">
                                        <th>To City</th>    
                                        <td>@{{viewData.toCityName}}</td>

                                        <th>To Center</th>  
                                        <td>@{{viewData.toCenterName}}</td>
                                    </tr>


                                    <tr>
                                        <th ng-if="viewData.invoice_number != null">Invoice Number</th>
                                        <td ng-if="viewData.invoice_number != null">@{{viewData.invoice_number}}</td>

                                        <th ng-if="viewData.company_name != null">Company</th>
                                        <td ng-if="viewData.company_name != null">@{{viewData.company_name}}</td>
                                    </tr>

                                    <tr ng-if="viewData.document != null">
                                        <th>Document</th>
                                        <td colspan="3"><a href="{{url('/')}}/@{{viewData.document}}" target="_blank">View</a></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>


                        <div class="row mt-3"> 
                            <div class="col-md-12">
                                <label class="bold">Remark</label><br><hr>
                                <label class="mr-5">@{{viewData.remark}}</label>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Items</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in viewitems">
                                        <td>@{{item.item_name}}</td>
                                        <td>@{{item.quantity}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div ng-if="viewData.status == 0">
                        <hr>
                        <label>Change Status</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="radio" name="type" ng-value="1" ng-model="approveOrReject.type" > Approve &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="type" ng-value="2" ng-model="approveOrReject.type" > Reject
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-md-8 form-group" >
                                <textarea ng-model="approveOrReject.remarks" class="form-control" placeholder="Enter remarks"></textarea>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" ng-click="changeStatus()">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn " data-dismiss="modal" aria-hidden="true"  ng-disabled="processing_req" >Close</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>