app.controller('Company_controller', function($scope, $http, DBService){
  
  $scope.loading = false;
  $scope.dataset = [];

  $scope.filter = {
    page_no : 1,
    max_per_page : 20,
    max_page: 1,
    order_by: '',
    order_type: 'ASC',
    export: false,
    show: false
  }
  $scope.companyData = {};
  $scope.total = 0

  $scope.init = function(){
    $scope.loading = true;

    DBService.postCall($scope.filter,'/inventory/companies/get-companies')
    .then(function(data){
      if (data.success) {
        if($scope.filter.export){
          window.open(data.excel_link,'_blank');
        } else {
          $scope.dataset = data.items;
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
    console.log("exporting");
    $scope.exporting = true;
    $scope.filter.export = true;
    $scope.getList();
  }

  $scope.addCompany = function(){
    $scope.companyData = {};
    $("#company_modal").modal('show');
  }

  $scope.saveCompany = function(){
    $scope.processing_req = true;
    DBService.postCall($scope.companyData,'/inventory/companies/save-company').then(function(data){
      if (data.success) {
        bootbox.alert(data.message);
        $("#company_modal").modal('hide');
      }else{
        bootbox.alert(data.message);
      }
      $scope.init();
      $scope.processing_req = false;
    });
  }

  $scope.editCompany = function(data){
    $scope.companyData = {};
    $scope.companyData = JSON.parse(JSON.stringify(data));
    $("#company_modal").modal('show');
  }

  $scope.deleteCompany = function(id, index){
    bootbox.confirm("Are you sure?", (check)=>{
        if(check){
          DBService.getCall('/inventory/companies/delete-companies/'+id).then(function(data){
            if (data.success) {
              bootbox.alert(data.message);
              $scope.dataset.splice(index,1);
              $scope.loading = false;
            }
          });
        }
    });
  }

});


