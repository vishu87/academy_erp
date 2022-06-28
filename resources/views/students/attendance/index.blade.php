@extends('layout')

@section('sub_header')
  <div class="sub-header">
    <div class="table-div">
      <div>
        <h4 class="fs-18 bold" style="margin:0;">Attendance</h4>
      </div>
      <div class="text-right">
      </div>
    </div>
  </div>
@endsection

@section('content')

<div class="ng-cloak" ng-controller="Stu_Attendance_Controller" ng-init="getCityCenter('attendance'); getSessionList()"> 

  <div ng-if="loading" class="text-center mt-5 mb-5">
    <div class="spinner-grow" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="portlet" ng-if="filterData.show" ng-hide="loading">
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

                      <div class="col-md-6 form-group">
                        <label class="label-control">Month</label>
                        <select class="form-control" ng-model="filterData.month">
                          <option ng-value=0>Select</option>
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

                      <div class="col-md-6 form-group">
                        <label class="label-control">Year</label>
                        <select class="form-control" ng-model="filterData.year">
                          <option ng-value=0>Select</option>
                          <option value="2022">2022</option>
                          <option value="2021">2021</option>
                          <option value="2020">2020</option>
                        </select>
                      </div>

                  </div>
                  <div class="row">
                      <div class="col-md-12">
                        <button  class="btn btn-primary" ng-click="getStudents()">
                        Apply <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
                        </button>
                      </div>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9" ng-if="filterData.group_id">
      @include("students.attendance.list")
    </div>
  </div>
  
  @include('students.student_personal_detail_modal')
</div>

@endsection
