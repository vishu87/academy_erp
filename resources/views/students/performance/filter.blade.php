<div class="portlet" ng-hide="loading">
    <div class="portlet-body">
      <div class="filters">
          <form name="filterForm" ng-submit="" novalidate>
              <div class="row">
                  <div class="col-md-12 form-group">
                    <label class="label-control">City</label>
                    <select class="form-control" ng-model="filterData.city_id">
                      <option ng-value=0>Select</option>
                      <option  ng-repeat="city in cityCenter.city" ng-value="city.value">
                      @{{city.label}}</option>
                    </select>
                  </div>

                  <div class="col-md-12 form-group">
                    <label class="label-control">Center</label>
                    <select class="form-control" ng-model="filterData.center_id">
                      <option ng-value=0>Select</option>
                      <option  ng-repeat="center in cityCenter.center" ng-value="center.value" ng-if="filterData.city_id == center.city_id">
                      @{{center.label}}</option>
                    </select>
                  </div>

                  <div class="col-md-12 form-group">
                    <label class="label-control">Group</label>
                    <select class="form-control" ng-model="filterData.group_id">
                      <option ng-value=0>Select</option>
                      <option  ng-repeat="group in cityCenter.group" ng-value="group.value" ng-if="filterData.center_id == group.center_id">
                      @{{group.label}}</option>
                    </select>
                  </div>

                  <div class="col-md-12 form-group">
                    <label class="label-control">Performance Session</label>
                    <select class="form-control" ng-model="filterData.session_id">
                      <option ng-value=0>Select</option>
                      <option  ng-repeat="session in sessionList" ng-value="session.id">
                      @{{session.name}}</option>
                    </select>
                  </div>

              </div>
              <div class="row">
                  <div class="col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="getStudents()" ng-disabled="processing_req" >Apply<span ng-show="processing_req" class="spinner-border spinner-border-sm"></span></button>
                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>