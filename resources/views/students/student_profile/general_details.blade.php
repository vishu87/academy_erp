<div class="row">
  <div class="col-md-12">
    <div class="static-info">
      <span><i class="icons icon-calendar"></i> DOB</span> @{{student.dob}}
    </div>    
  </div>
  <div class="col-md-12">
    <div class="static-info">
      <span><i class="icons icon-graduation"></i> School</span> @{{student.school ? student.school : 'NA'}}
    </div>
  </div>

  <div class="col-md-12">
    <div class="static-info">
      <span><i class="icons icon-people"></i> Guardians</span>
      
        <div class="table-div full" ng-repeat="item in student.guardians">
          <div>
            @{{item.name}} (@{{ item.relation }}) 
          </div>
          <div class="text-right" style="width: 80px">
            <button class="btn btn-sm" style="width: 30px" ng-if="item.mobile" ng-click="showInfo(item.mobile,'mobile')"><i class="icons icon-phone"></i></button> <button class="btn btn-sm" ng-if="item.email" style="width: 30px" ng-click="showInfo(item.email,'email')"><i class="icons icon-envelope-open"></i></button>
          </div>
        </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="static-info">
      <span><i class="icons icon-directions"></i> Address</span> @{{student.address}}, @{{student.address_city}}, @{{student.state_name}}
    </div>
  </div>


  

</div>