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

    DBService.postCall($scope.filter,'/inventory/request/get-request')
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
      DBService.postCall({Tag:tag},
       "/api/get-state-city-center-data")
      .then(function (data){  
        if (data.success) {
          $scope.state_city_center = data;
        }
      });
    } 

  $scope.company = function(){
    DBService.postCall($scope.filter,'/inventory/request/get-companies')
    .then(function(data){
      if (data.success) {
          $scope.companies = data.companies;
      } 
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


  $scope.addItem = function(){
    $scope.requestData.items.push(JSON.parse(JSON.stringify($scope.optionItem)));
  }

  $scope.removeItem = function(index){
    $scope.items.splice(index,1);
  }

  $scope.uploadDocument = function (file) {

      var url = base_url + '/inventory/request/upload-document';
      Upload.upload({
          url: url,
          data: {
              media: file
          }
      }).then(function(response) { 
          $scope.fileObj.link     = response.data.media_link;
          $scope.fileObj.document = response.data.media;
      });
  }

  $scope.removeFile = function(){
      $scope.fileObj.document = '';
  }

  $scope.saveRequest = function(){

    $scope.loading = true;
    DBService.postCall($scope.requestData,'/inventory/request/save-request')
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

    DBService.getCall('/inventory/request/request-data/'+id).then(function(data){
        if(data.success) {
            if(id != 0){
              $scope.requestData = data.request;
            }
        }
        $scope.company();
        $scope.getStateCityCenter();
        $scope.allItems();
    });
  }


  $scope.viewInventoryRequest = function(id){
    $scope.rowId = id;
    DBService.getCall('/inventory/request/view-data/'+id).then(function(data){
        if (data.success) {
          $scope.viewData  = data.request;
          $scope.viewitems = data.items;
          $("#request-view-modal").modal('show');
        } 
    });
  }

  $scope.changeStatus = function(){
    $scope.approveOrReject.id = $scope.rowId;
    DBService.postCall($scope.approveOrReject,'/inventory/request/approve-or-reject').then(function(data){
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
          DBService.getCall('/inventory/request/delete-data/'+id).then(function(data){
              if(data.success){
                bootbox.alert(data.message);
                $scope.dataset.splice(index,1);
              } 
          });
        }
    });
  }

  $scope.allItems = function(){
    DBService.postCall($scope.filter,'/inventory/request/all-items').then(function(data){
      if(data.success){
          $scope.allItems = data.allItems;
      } 
    });
  }

});


