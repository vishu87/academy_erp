@extends('layout')


@section('content')

<div class="ng-cloak" ng-controller="Staff_Attendance_Controller" ng-init="getCityCenter('staff-attendance')"> 

  <div class="page-header row">
    <div class="col-md-6 col-6">
      <h3>Staff Attendance</h3>
    </div>
    <div class="col-md-6 col-6 text-right">
      <button class="btn btn-primary" ng-click="filterData.show = (filterData.show?false:true)">
      @{{filterData.show?'Hide':'Show'}} Filters</button>
    </div>
  </div>

  <div ng-if="loading" class="text-center mt-5 mb-5">
    <div class="spinner-grow" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>

  <div class="portlet" ng-if="filterData.show" ng-hide="loading">
    <div class="portlet-body">
      <div class="filters">
          <form name="filterForm" ng-submit="" novalidate>
              <div class="row">
                  <div class="col-md-2 form-group">
                    <label class="label-control">City</label>
                    <select class="form-control" ng-model="filterData.city_id">
                      <option value="">Select</option>
                      <option  ng-repeat="city in cityCenter.city" ng-value="city.value">
                      @{{city.label}}</option>
                    </select>
                  </div>

                  <div class="col-md-2 form-group">
                    <label class="label-control">Month</label>
                    <select class="form-control" ng-model="filterData.month">
                      <option value="">Select</option>
                      <option value="01">Jan</option>
                      <option value="02">Feb</option>
                      <option value="03">Mar</option>
                      <option value="04">Apr</option>
                      <option value="05">May</option>
                      <option value="06">Jun</option>
                      <option value="07">Jul</option>
                      <option value="08">Aug</option>
                      <option value="09">Sep</option>
                      <option value="10">Oct</option>
                      <option value="11">Nov</option>
                      <option value="12">Dec</option>
                    </select>
                  </div>

                  <div class="col-md-2 form-group">
                    <label class="label-control">Year</label>
                    <select class="form-control" ng-model="filterData.year">
                      <option value="">Select</option>
                      @for($year = date("Y"); $year > date("Y") - 2; $year--)
                      <option value="{{$year}}">{{$year}}</option>
                      @endfor
                    </select>
                  </div>

              </div>
              <div class="row">
                  <div class="col-md-2">
                    <button  class="btn btn-primary" ng-click="getStaff()" ng-disabled="processing">
                    Apply <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
                    </button>
                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>
    
  <div class="row">
    <div class="col-md-12">
      @include("users.staff.attendance.list")
    </div>
  </div>

</div>

@endsection
