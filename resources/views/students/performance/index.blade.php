@extends('layout')

@section('sub_header')
  <div class="sub-header">
    <div class="table-div">
      <div>
        <h4 class="fs-18 bold" style="margin:0;">Performance</h4>
      </div>
      <div class="text-right">
      </div>
    </div>
  </div>
@endsection

@section('content')

<div class="ng-cloak" ng-controller="Stu_Performance_Controller" ng-init="getCityCenter('performance'); getSessionList()"> 


  <div ng-if="loading" class="text-center mt-5 mb-5">
    <div class="spinner-grow" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
    
  <div class="row">
    <div class="col-md-3">
      @include("students.performance.filter")
    </div>
    <div class="col-md-3">
      @include("students.performance.performance_list")
    </div>
    <div class="col-md-6" ng-if="studentRecord.student_id">
      @include("students.performance.performance_card")
    </div>
  </div>

</div>

@endsection

@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/students_performance_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection
