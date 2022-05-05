app.controller("Stu_Attendance_Controller", function($scope, $http, DBService, Upload) {
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


  $scope.getStudents = function(){
    $scope.processing = true;
    DBService.postCall({
      group_id: $scope.filterData.group_id,
      month: $scope.filterData.month,
      year: $scope.filterData.year
    },
    '/api/student/attendance')
    .then(function(data){
        $scope.dates = data.dates;
        $scope.students = data.students;
        $scope.processing = false;
    });
  }

  $scope.addAttendance = function(date){
    for (var i = 0; i < $scope.students.length; i++) {
      var check_present = $scope.students[i].present.indexOf(date);
      var check_absent = $scope.students[i].absent.indexOf(date);
      
      if(check_present == -1){
        $scope.students[i].present.push(date);
      }

      if(check_absent > -1){
        $scope.students[i].absent.splice(check_absent,1);
      }

    }
  }

  $scope.switchAttendance = function(student, date){
    var idx_present = student.present.indexOf(date);
    var idx_absent = student.absent.indexOf(date);

    if(idx_present == -1 && idx_absent == -1){
      student.present.push(date);
    } else if(idx_present > -1 ) {
      student.present.splice(idx_present,1);
      student.absent.push(date);
    } else if(idx_absent > -1 ) {
      student.absent.splice(idx_present,1);
    }

  }

  $scope.saveAttendance = function(){
    $scope.saveprocessing = true;
    DBService.postCall({
      students:$scope.students,
      dates:$scope.dates,
      group_id: $scope.filterData.group_id
    },"/api/student/attendance/save-attendance")
    .then(function(data){
        if (data.success) {
            $scope.saveprocessing = false;
            $scope.getStudents();
            bootbox.alert(data.message);
        }else{
            bootbox.alert(data.message);
            $scope.saveprocessing = false;
        }
    });
  }

});
