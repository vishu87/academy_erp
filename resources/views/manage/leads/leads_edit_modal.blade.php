<div class="modal fade in" id="addNote" role="dialog" data-backdrop="static" style="overflow: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body" style="padding:0">
                <div class="row">
                    <div class="col-md-5" style="background-color: #F7f7f7;">
                        <div style="padding: 20px;">
                            <div class="table-div full">
                                <div class="">
                                    <h4 style="display: inline-block;">Lead Details</h4>
                                    <button type="button" class="btn btn-sm btn-light" ng-click="edit_lead = !edit_lead"> @{{edit_lead ? 'Cancel' : 'Edit'}}</button>
                                </div>
                            </div>
                            @include('manage.leads.leads_info')
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div style="padding: 20px; padding-left: 10px">
                            @include('manage.leads.leads_note')
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
