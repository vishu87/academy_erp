app.controller('Students_controller', function($scope, $http, DBService, Upload){
  
  $scope.loading = false;
  $scope.dataset = [];
  $scope.params = [];
  $scope.filter = {
    page_no : 1,
    max_per_page : 20,
    max_page: 1,
    order_by: '',
    order_type: 'ASC',
    export: false,
    show: false,
    pending_renewal: false,
    status: [0]
  }
  $scope.total = 0;
    $scope.myImage = '';
    $scope.myCroppedImage = '';

  $scope.init = function(){
    $scope.getList();
  }

  $scope.getList = function(){
    $scope.loading = true;

    DBService.postCall($scope.filter,'/api/student/get-list')
    .then(function(data){
      if (data.success) {
        if($scope.filter.export){
          window.open(data.excel_link,'_blank');
        } else {
          $scope.dataset = data.students;
          $scope.total = data.total;
          $scope.filter.max_page = Math.ceil($scope.total/$scope.filter.max_per_page)
        }
      } else {
        bootbox.alert(data.message);
      }
      $scope.loading = false;
      $scope.filter.searching = false;
      $scope.filter.clearing = false;
      $scope.filter.exporting = false;
      $scope.filter.export = false;
      $scope.setPagination();
    });
  }

  $scope.getParams = function(){
    $scope.getStateCityCenter('st-profile')
  }

  $scope.getStateCityCenter = function(tag){
    DBService.postCall({Tag:tag},
     "/api/get-state-city-center-data")
    .then(function (data){  
      if (data.success) {
        $scope.state_city_center = data;
      }
    });
  }

  $scope.getParams();

  $scope.searchList = function(){
    $scope.filter.page_no = 1;
    $scope.filter.searching = false;
    $scope.getList();
  }

  $scope.clear = function(){
    $scope.filter = {
      page_no : 1,
      max_per_page : 20,
      max_page: 1,
      clearing : true
    };
    $scope.getList();
  }

  $scope.exportList = function(){
    $scope.exporting = true;
    $scope.filter.export = true;
    $scope.getList();
  }

});

