app.controller('SalesDashboardCtrl', function($scope,$rootScope,DBService){

  $scope.filter = {
    report_type: report_type,
    month: "",
    year: "",
    date_ref: ""
  }

  $scope.cities = [];

  $scope.dates = [];
  $scope.weeks = [];

  $scope.layout = 0;

  $scope.layout2 = ["cash_flow","demo_scheduled","demo_attended","verbal_confirmation","enrolled","renewed"];

  $scope.applyFilter = function(){
    $scope.processing = true;

    // if($scope.filter.report_type == "" ) return;

    DBService.postCall($scope.filter,'/api/sales-dashboard/init').then(function(data){
      if(data.success){
        $scope.all_india = data.all_india;
        $scope.cities = data.cities;
        $scope.members = data.members;
        $scope.dates = data.dates;
        $scope.weeks = data.weeks;

        if($scope.filter.report_type == "lms_pending"){
          $scope.layout = 1;
        } else if($scope.layout2.indexOf($scope.filter.report_type) > -1){
          $scope.layout = 2;
        } else {
          $scope.layout = 0;
        }

      }

      $scope.processing = false;

    });
  }

});