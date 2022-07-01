app.controller('Request_controller', function($scope, $http, DBService, Upload){
  $scope.loading = false;
  $scope.dataset = [];
  $scope.price_type ={};
  $scope.viewData = {};
  $scope.requestData = {
    items:[{}]
  };
  $scope.fileObj = {
    document : '',
    link : ''
  };
  $scope.optionItem = {};
  $scope.filter = {
    page_no : 1,
    max_per_page : 20,
    max_page: 1,
    order_by: '',
    order_type: 'ASC',
    export: false,
    show: false
  }
  $scope.total = 0;
  $scope.approveOrReject = {};

  $scope.init = function(){
    $scope.loading = true;

    DBService.postCall($scope.filter,'/api/inventory/request/get-request')
    .then(function(data){
      if (data.success) {
        if($scope.filter.export){
          window.open(data.excel_link,'_blank');
        } else {
          $scope.dataset = data.request;
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
      DBService.postCall({Tag:tag},"/api/get-state-city-center-data")
      .then(function (data){  
        if (data.success) {
          $scope.state_city_center = data;
        }
      });
    } 

  $scope.company = function(){
    DBService.postCall($scope.filter,'/api/inventory/request/get-companies')
    .then(function(data){
      if (data.success) {
          $scope.companies = data.companies;
      } 
    });
  }

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
    $scope.getList();
  }

  $scope.exportList = function(){
    console.log("exporting");
    $scope.exporting = true;
    $scope.filter.export = true;
    $scope.getList();
  }


  $scope.addItem = function(){
    $scope.requestData.items.push(JSON.parse(JSON.stringify($scope.optionItem)));
  }

  $scope.removeItem = function(index){
    $scope.requestData.items.splice(index,1);
  }

  $scope.uploadDocument = function (file) {

      var url = base_url + '/uploads/file';
      Upload.upload({
          url: url,
          data: {
              file: file
          }
      }).then(function(response) { 
        console.log(response.data);
          $scope.requestData.link     = response.data.url;
          $scope.requestData.document = response.data.path;
      });
  }

  $scope.removeFile = function(){
      $scope.requestData.document = '';
  }

  $scope.saveRequest = function(){
    $scope.loading = true;
    DBService.postCall($scope.requestData,'/api/inventory/request/save-request')
    .then(function(data){
      if (data.success) {
        window.location = base_url+"/inventory/request"
        $scope.loading = false;
      }else{
        bootbox.alert(data.message);
      }
    });
  }

  $scope.formData = function(id){

    DBService.getCall('/api/inventory/request/request-data/'+id).then(function(data){
        if(data.success) {
            if(id != 0){
              $scope.requestData = data.request;
            }
        }
        $scope.company();
        $scope.getStateCityCenter('inv_user');
        $scope.allItems();
    });
  }


  $scope.viewInventoryRequest = function(id){
    $scope.rowId = id;
    DBService.getCall('/api/inventory/request/view-data/'+id).then(function(data){
        if (data.success) {
          $scope.viewData  = data.request;
          $scope.viewitems = data.items;
          $("#request-view-modal").modal('show');
        } 
    });
  }

  $scope.changeStatus = function(){
    $scope.approveOrReject.id = $scope.rowId;
    DBService.postCall($scope.approveOrReject,'/api/inventory/request/approve-or-reject').then(function(data){
      if (data.success) {
        bootbox.alert(data.message);
        $("#request-view-modal").modal('hide');
        for (var i = 0; i < $scope.dataset.length; i++) {
          if($scope.dataset[i].id == $scope.rowId){
            $scope.dataset[i].status = data.new_status;
          }
        }
      } else {
        bootbox.alert(data.message);
      }
      $scope.init();
    });
  }

  $scope.deleteRequest = function(id, index){
    bootbox.confirm("Are you sure?", (check)=>{
        if(check){
          DBService.getCall('/api/inventory/request/delete-data/'+id).then(function(data){
              if(data.success){
                bootbox.alert(data.message);
                $scope.dataset.splice(index,1);
              } 
          });
        }
    });
  }

  $scope.allItems = function(){
    DBService.postCall($scope.filter,'/api/inventory/request/all-items').then(function(data){
      if(data.success){
          $scope.allItems = data.allItems;
      } 
    });
  }

});


