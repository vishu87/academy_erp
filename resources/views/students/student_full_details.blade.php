@extends('layout')

@section('sub_header')
  <div class="sub-header">
    <div class="table-div full">
      <div>
        <h4 class="fs-18 bold" style="margin:0;">Student Profile</h4>
      </div>
      <div class="text-right">
        <a href="{{url('/students')}}" > <i class="fa fa-angle-left"></i> Go Back</a>
      </div>
    </div>
  </div>
@endsection

@section('content')

<div ng-controller="Students_profile_controller" ng-init="sport_id = 1; student_details({{$id}});" class="ng-cloak">

  <div class="row">
    <div class="col-md-3">
      
      <div class="portlet">
        <div class="portlet-body" style="position: relative;">
              
              <div class="text-right" ng-if="student.edit_access" style="position: absolute; top:0; right:0">
                <a  href= "{{url('students/edit-student/'.$id)}}" class="btn btn-primary" style="border-radius: 0 0 0 5px;"><i class="icons icon-pencil"></i></a>
              </div>

              @include("students.student_profile.pic_details")
              @include('students.photo_popup')

              <div class="text-center mt-3">
                <h4 class="theme-color"><b>@{{student.name}}</b></h4>
                @{{student.group_name}}, @{{student.center_name}}, @{{student.city_name}}
              </div>

              @include("students.student_profile.more_details")

              <div class="mt-3">
                @include("students.student_profile.mobile_email")
              </div>

              <div class="mt-3">
                @include("students.student_profile.general_details")
              </div>

              <div class="mt-3">
                <div >
                  <button class="btn btn-light btn-block" ng-click="sendWelcomeEmail()" ng-disabled="processing_mail" >Send Welcome Email <span ng-show="processing_mail" class="spinner-border spinner-border-sm"></span></button>
                </div>
              </div>

        </div>
      </div>
    </div>

    <div class="col-md-9">
        @include("students.student_profile.details")
    </div>
  </div>

  @include('students.student_modal')
  @include('students.payment_modal')
  @include('students.subscription_modal')
  @include('payments.view_payment_modal')
 
</div>
@endsection