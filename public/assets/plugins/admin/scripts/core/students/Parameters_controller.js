app.controller("Parameters_controller", function($scope, $http, DBService) {
    
    $scope.sport_id = 0;
    $scope.loading = true;
    $scope.processing = false;
    $scope.formData = {};
    $scope.attrData = {};

    $scope.init = function(){
      DBService.getCall('/api/parameters/get-parameters/'+$scope.sport_id)
      .then(function(data){
        $scope.parameters = data.skill_categories;
        $scope.loading = false;
      });
    }  

    $scope.addCategory = function(){
      $("#category-modal").modal('show');
    }

    $scope.saveCategory = function(){
        $scope.processing = true;
        $scope.formData.sport_id = $scope.sport_id;
        DBService.postCall($scope.formData,'/api/parameters/save-category').then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
            $("#category-modal").modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  
    }

    $scope.editCategory = function(category){
      $scope.formData = JSON.parse(JSON.stringify(category));
      $("#category-modal").modal('show');
    }

    $scope.deleteCategory = function(id){

      bootbox.confirm("Are you sure?", (check)=>{
      if (check) {
        $scope.processing = true;
        DBService.getCall('/api/parameters/delete-category/'+id).then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  

      }
      });
    }

    $scope.addAttribute = function(parameterId){
      $scope.parameter_id = parameterId;
      $("#attribute-modal").modal('show');
    }

    $scope.saveAttribute = function(){
      $scope.attrData.category_id = $scope.parameter_id;
        $scope.processing = true;
        DBService.postCall($scope.attrData,'/api/parameters/save-attribute').then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
            $("#attribute-modal").modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  
    }

    $scope.editAttribute = function(attr){
      $scope.attrData = JSON.parse(JSON.stringify(attr));
      $("#attribute-modal").modal('show');
    }

    $scope.deleteAttribute = function(id){

      bootbox.confirm("Are you sure?", (check)=>{
      if (check) {
        
        $scope.processing = true;
        DBService.getCall('/api/parameters/delete-attribute/'+id).then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
          }
          $scope.processing = false;
        });

      }
      });

    }
});
