app.controller('Parents_controller', function($scope, $http, DBService, Upload){
  
  $scope.sport_id = 0;
  $scope.student = {};
  $scope.state_city_center = {};
  $scope.loading = true;
  $scope.processing = false;
  $scope.switchContent = 'payments';
  $scope.performance_category_id = 0;

  $scope.attendance = {
    weeks: []
  }

  $scope.p_categories = [];

  $scope.month = "";
  $scope.year = "";
  
  $scope.init = function(id){
    $scope.student_id = id;
    DBService.postCall({ student_id : id },
     "/api/parents/init")
    .then(function (data){  
        $scope.student = data.student;
        $scope.loading = false;
        $scope.getAttendance();
        $scope.getPerformanceReports();
        $scope.getPerformanceGraph();
    });
  }

  $scope.switchContentFun = function(tag){
    $scope.switchContent = tag;
  }

  $scope.getAttendance = function(){

    DBService.postCall({
      month : $scope.month,
      year : $scope.year,
    },"/api/student/attendance/"+$scope.student_id)
    .then(function (data){
        $scope.attendance.month_name = data.month_name;
        $scope.attendance.year = data.year;
        $scope.attendance.weeks = data.weeks;

        $scope.month = data.month;
        $scope.year = data.year;
        
    });
  }
  $scope.prev_month = function(){
      $scope.month--;
      if($scope.month == 0) {
          $scope.month = 12;
          $scope.year--;
      }
      $scope.getAttendance();
  }

  $scope.next_month = function(){
      $scope.month++;
      if($scope.month == 13) {
          $scope.month = 1;
          $scope.year++;
      }
      $scope.getAttendance();
  }

  $scope.getPerformanceReports = function(){
    DBService.postCall({
      
    },"/api/student/reports/"+$scope.student_id)
    .then(function (data){
        $scope.reports = data.reports;
    });
  }

  $scope.getPerformanceGraph = function(){
    $scope.p_data = {
      legends : [],
      labels : [],
      values: []
    };
    $scope.loading_graph = true;
    DBService.postCall({
      category_id: $scope.performance_category_id
    },"/api/student/performance-graph/"+$scope.student_id)
    .then(function (data){
        $scope.p_data = data.p_data;
        $scope.p_categories = data.categories;
        $scope.loading_graph = false;

    });

  }

  

});

