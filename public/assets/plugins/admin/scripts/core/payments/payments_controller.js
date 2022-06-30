app.controller("payments_controller", function($scope, $http, DBService) {

  $scope.pay_history = {};

  $scope.dataset = [];
  $scope.params = [];
  $scope.filter = {
    page_no : 1,
    max_per_page : 20,
    max_page: 1,
    order_by: '',
    order_type: 'ASC',
    export: false
  }
  
  $scope.loading = true;

  $scope.total = 0

  $scope.submitPayPrice = function(price){
    console.log(price);
  }

  $scope.getList = function(){
    $scope.loading = true;

    DBService.postCall($scope.filter,'/api/payments')
    .then(function(data){
      if (data.success) {
        $scope.getPaymentType();
        $scope.getParams();
        if($scope.filter.export){
          window.open(data.excel_link,'_blank');
        } else {
          $scope.dataset = data.payments;
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
    });
  }

  $scope.getList();


    $scope.getPaymentType = function(){
      DBService.getCall("/api/student/payment/get-type").then(function(data){
        if (data.success) {
          $scope.payModes = data.payModes;
        }
      });
    }

  $scope.getParams = function(){
    $scope.getStateCityCenter('pt-view')
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

  $scope.searchList = function(){
    $scope.filter.page_no = 1;
    $scope.filter.searching = false;
    $scope.getList();
  }

  $scope.viewPayment = function(history_id){
    DBService.getCall("/api/student/payment/view-payment/"+history_id)
      .then(function(data){
        $scope.payment = data.payment;
       $("#viewPaymentModal").modal("show"); 
    })
  }

});