app.controller("Roles_controller", function($scope, $http, DBService) {
    
    $scope.edit = false;
    $scope.users_roles = {};
    $scope.users_right_names = {};
    $scope.add_roles = {
      access_rights:[]
    };

    $scope.roles_init = function(){
    DBService.postCall({},"/api/users/roles/list")
    .then(function(data){
      $scope.users_roles = data.user_roles;
      $scope.users_right_names = data.user_access_rights;
    });

   }

   $scope.add_user_roles = function(){
      $scope.processing_req = true;
      
      DBService.postCall($scope.add_roles,"/api/users/roles/add")
      .then(function(data){
        if (data.success) {
          $scope.roles_init();
          $("#add_roles_modal").modal("hide");
        }
        bootbox.alert(data.message);
        $scope.add_roles.show = false;
        $scope.processing_req = false;
      });
   }

   $scope.edit_roles = function(data){
      $scope.edit = true;
      $scope.add_roles= data;
      if (!$scope.add_roles.show) {
          $scope.add_roles.show = true;
      }
      $("#add_roles_modal").modal("show");
   }

  $scope.delete_roles = function(data){
    bootbox.confirm("Are you sure?", (result)=>{
      if (result) {
         DBService.postCall({
          id : data.id
         },
        "/api/users/roles/delete")
        .then(function(data){
          if (data.success == true) {
            $scope.roles_init();
          }
          bootbox.alert(data.message);
        }); 
      }
    });
  }

  $scope.add_rights = function(id){
    if ($scope.add_roles.access_rights.includes(id)) {
      $scope.add_roles.access_rights.splice($scope.add_roles.access_rights.indexOf(id),1);
    }
    else{
    $scope.add_roles.access_rights.push(id);
    }
  }

  $scope.addUserRoles = function(){
    $scope.edit = false;
    $scope.add_roles = {
      access_rights:[]
    };
    $("#add_roles_modal").modal("show");
  }

  $scope.hideModal = function(modal_id){
    $("#"+modal_id).modal("hide");
  }


});
