<div id="addStudentModal" class="modal fade in small-modal modal-overflow" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document"> 
      <div class="modal-content">
        <div class="modal-header">
          <h4>Success!</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
            @{{message}}
          </div>
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-primary btn-block" href="@{{ location }}"> Go To Profile </a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-light btn-block" href="#" ng-click="reloadRoute()"><i class="icons icon-plus"></i> Add New Student </a>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>