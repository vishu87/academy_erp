app.controller("DropDownMasterController", function($scope, $http, DBService) {
    $scope.loading = true;
    $scope.processing = false;
    $scope.formData = {};
    $scope.attrData = {};

    $scope.init = function(){
      DBService.getCall('/api/group-type/init').then(function(data){
        $scope.group_types = data.group_types;
        $scope.loading = false;
      });
    }  

    $scope.addGroupType = function(){
      $scope.formData = {};
      $("#group-type-modal").modal('show');
    }

    $scope.submit = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData,'/api/group-type/save').then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
            $("#group-type-modal").modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  
    }

    $scope.edit = function(group_type){
      $scope.formData = {};
      $scope.formData = JSON.parse(JSON.stringify(group_type));
      $("#group-type-modal").modal('show');
    }

    $scope.delete = function(id, index){

      bootbox.confirm("Are you sure?", (check)=>{
      if (check) {
        $scope.processing = true;
        DBService.getCall('/api/group-type/delete/'+id).then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.group_types.splice(index,1);
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  

      }
      });
    }
});
