app.controller('stock_controller', function($scope, $http, DBService, Upload){
  
  $scope.loading = false;
  $scope.dataset = [];
  $scope.filter = {
    page_no : 1,
    max_per_page : 10,
    max_page: 1,
    order_by: '',
    order_type: 'ASC',
    export: false,
    show: false
  }
  $scope.total = 0

  $scope.init = function(){
    $scope.loading = true;

    DBService.postCall($scope.filter,'/inventory/current-stock/get-stock')
    .then(function(data){
      if (data.success) {
        if($scope.filter.export){
          window.open(data.excel_link,'_blank');
        } else {
          $scope.dataset = data.stocks;
          $scope.total   = data.total;
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

    $scope.getStateCityCenter = function(tag){
      DBService.postCall({Tag:tag},
       "/api/get-state-city-center-data")
      .then(function (data){  
        if (data.success) {
          $scope.state_city_center = data;
        }
      });
    } 

    $scope.getStateCityCenter();

  $scope.searchList = function(){
    $scope.filter.page_no = 1;
    $scope.filter.searching = false;
    $scope.init();
  }

  $scope.clear = function(){
    $scope.filter = {
      page_no : 1,
      max_per_page : 20,
      max_page: 1,
      clearing : true
    };
    $scope.init();
  }

  $scope.exportList = function(){
    console.log("exporting");
    $scope.exporting = true;
    $scope.filter.export = true;
    $scope.init();
  }

});


