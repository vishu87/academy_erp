<div class="modal" id="student_personal_detail" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Student Profile</h4>
          <button type="button" class="close" data-dismiss="modal" >&times;</button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4 text-center">
              <img class="round" ng-src="@{{student.pic}}" ng-if="student.pic" style="border: 4px solid @{{student.color}}">
            </div>
            <div class="col-md-8">
                <h4 class="theme-color"><b>@{{student.name}}</b></h4>
                <div class="mb-3">
                  <i class="fa fa-location"></i>@{{student.group_name}}, @{{student.center_name}}, @{{student.city_name}}
                </div>
                @include("students.student_profile.more_details")

                <div class="mt-3">
                  @include("students.student_profile.mobile_email")
                </div>

                <div class="mt-3">
                  @include("students.student_profile.general_details")
                </div>

            </div>
          </div>
         <div class="modal-footer">
          <a class="btn btn-primary" href="{{url('students/student_details')}}/@{{student.id}}" target="_blank">View Profile</a>
          <button type="button" class="btn btn-secondary" class="close" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>