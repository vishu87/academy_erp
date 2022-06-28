app.controller("Staff_Attendance_Controller", function($scope, $http, DBService, Upload) {
  $scope.cityCenter = {};
  $scope.filterData ={
    show: true,
  };
  $scope.students = [];
  $scope.dates = [];
  $scope.loading = true;
  $scope.processing = false;

  $scope.getCityCenter = function(tag){
    DBService.postCall({Tag:tag},'/api/get-state-city-center-data')
    .then(function(data){
      $scope.cityCenter = data;
      $scope.loading = false;
    });
  }

  // --------------------------------------------------------------------------------------
  // Stff addAttendance

  $scope.getStaff = function(){
    $scope.processing = true;
    DBService.postCall({
      city_id:$scope.filterData.city_id,
      month: $scope.filterData.month,
      year: $scope.filterData.year
    },'/api/users/attendance')
    .then(function(data){
        $scope.dates = data.dates;
        $scope.staffMembers = data.staffMembers;
        $scope.processing = false;
    });
  }

   $scope.addSatffAttendance = function(date){
    for (var i = 0; i < $scope.staffMembers.length; i++) {
      var check_present = $scope.staffMembers[i].present.indexOf(date);
      var check_absent = $scope.staffMembers[i].absent.indexOf(date);
      
      if(check_present == -1){
        $scope.staffMembers[i].present.push(date);
      }

      if(check_absent > -1){
        $scope.staffMembers[i].absent.splice(check_absent,1);
      }

    }
  }
  

  $scope.switchStaffAttendance = function(staff, date){
    var idx_present = staff.present.indexOf(date);
    var idx_absent = staff.absent.indexOf(date);

    if(idx_present == -1 && idx_absent == -1){
      staff.present.push(date);
    } else if(idx_present > -1 ) {
      staff.present.splice(idx_present,1);
      staff.absent.push(date);
    } else if(idx_absent > -1 ) {
      staff.absent.splice(idx_present,1);
    }

  }

  $scope.saveStaffAttendance = function(){
    $scope.saveprocessing = true;
    DBService.postCall({
      staffMembers : $scope.staffMembers,
      dates : $scope.dates,
      city_id : $scope.filterData.city_id,
    },
      "/api/users/attendance/save-attendance")
    .then(function(data){
        if (data.success) {
            $scope.saveprocessing = false;
            $scope.getStaff();
            bootbox.alert(data.message);
        }else{
            bootbox.alert(data.message);
            $scope.saveprocessing = false;
        }
    });
  }

});
