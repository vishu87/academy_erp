@extends('layout')

@section('content')

<div ng-controller="Parents_controller" ng-init="init( {{ Session::get('user_student_id') }} );" class="ng-cloak">

  <div class="row">
    <div class="col-md-3">
      
      <div class="portlet">
        <div class="portlet-body" style="position: relative;">
              
              @include("parents.profile.pic_details")

              <div class="text-center mt-3">
                <h4 class="theme-color"><b>@{{student.name}}</b></h4>
                @{{student.group_name}}, @{{student.center_name}}, @{{student.city_name}}
              </div>

              @include("parents.profile.more_details")

              <div class="mt-3">
                @include("parents.profile.mobile_email")
              </div>

              <div class="mt-3">
                @include("parents.profile.general_details")
              </div>

        </div>
      </div>
    </div>

    <div class="col-md-9">
        @include("parents.profile.details")
        @include("payments.view_payment_modal")
        @include("students.subscription_modal")
    </div>
  </div>
 
</div>
@endsection
