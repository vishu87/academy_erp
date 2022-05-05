<div class="modal fade in" id="company_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Company</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="modal-body small-form" >
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Company Name</label>
                        <input type="text" ng-model="companyData.company_name" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Contact Number</label>
                        <input type="text" ng-model="companyData.contact_no" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Address</label>
                        <input type="text" ng-model="companyData.address" class="form-control">
                    </div> 
                </div>
                <button type="button" ng-click="saveCompany()" class="btn btn-info">@{{companyData.id ? 'Update Company' : 'Save Company'}}</button>
            </div>
        </div>
    </div>
</div>