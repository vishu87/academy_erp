app.controller("AccessRights_controller", function($scope, $http, DBService) {
    
    $scope.edit = false;
    $scope.users_roles = {};
    $scope.users_right_names = {};
    $scope.add_access_rights = {};

    $scope.accessRightsInit = function(){
    DBService.postCall({},"/api/users/access-rights/list")
    .then(function(data){
      $scope.users_right_names = data.user_access_rights;
      $scope.access = data.access;
    });

   }

   $scope.addAccessRights = function(){
      console.log('--',$scope.add_access_rights);
      DBService.postCall({rights_data:$scope.add_access_rights},
        "/api/users/access-rights/add")
      .then(function(data){
          if (data.success.success == true){
            alert("Access Rights added successfully");
            location.reload();
          }
      })
    }

    $scope.updateAccessRights = function(){
      console.log('--',$scope.add_access_rights);
      DBService.postCall({rights_data:$scope.add_access_rights},
        "/api/users/access-rights/update")
      .then(function(data){
          if (data.success.success == true){
            alert("Access Rights updated successfully");
            location.reload();
          }
      })
    }

    $scope.editRights = function(data){
      $scope.edit = true;
      $scope.add_access_rights = data;
      if (!$scope.add_access_rights.show) {
        $scope.add_access_rights.show = true;
      }
       window.scroll(0,0);
    }

    $scope.deleteRights = function(roles){
      if (confirm("Are you sure to delete Access Right")){
        DBService.postCall({roles:roles},
          "/api/users/access-rights/delete")
        .then(function(data){
          if (data.success == true) {
            alert("Rights deleted successfully");
            location.reload();
          }
        })
      }
    }

});
