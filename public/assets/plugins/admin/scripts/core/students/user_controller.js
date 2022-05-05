app.controller("User_controller", function($scope, $http, DBService) {
    $scope.user_id = '';
    $scope.edit = false; 
    $scope.filterData ={
      show: true,
    };
    $scope.access_location_data = {
      centers_ids:[],
      groups_ids:[]
    };
    $scope.access_right_type =
    [
      {'id':1, 'type':"All Access"},
      {'id':2, 'type':"City"},
      {'id':3, 'type':"Center"},
      {'id':4, 'type':"Group"}
    ];
    
    $scope.ac_provideer = false;
    $scope.add_user = { role:0, gender:"", city_id:"0" };
    $scope.users_roles = [];

    $scope.userInit = function(){
      $scope.getCityCenter('users');
      DBService.postCall({},"/api/users/list")
      .then(function(data){
        $scope.users_list = data.users_list;
        $scope.all_access = data.access;
      });
    }

    $scope.getRoles = function(){
      DBService.getCall("/api/users/get-roles")
      .then(function(data){
        if(data.success){
          $scope.users_roles = data.user_roles;
        }
      });
    }

    $scope.getCityCenter = function(tag){
      DBService.postCall({Tag:tag},'/api/get-state-city-center-data')
      .then(function(data){
        $scope.cityCenter = data;
        $scope.loading = false;
      });
    }

    $scope.editUserInit = function(user_id){

      $scope.user_id = user_id;
      $scope.access_location_data.user_id = user_id;

      $scope.getLocations('users');

      DBService.getCall("/api/users/edit/" + user_id)
      .then(function(data){
        if(data.success){
          $scope.add_user = data.user;
          $scope.sports = data.sports;
          $scope.getUserAccess();
        }
      });
      $scope.getRoles();
    
    }

    $scope.getLocations = function(tag){
      DBService.postCall({Tag:tag},"/api/get-state-city-center-data")
      .then(function(data){
        $scope.all_city_list = data.city;
        $scope.all_center_list = data.center;
        $scope.all_group_list = data.group;
        $scope.ac_provideer = data.all_access;
      });
    }

    $scope.saveUser = function(){
      $scope.processing_req = true;
      DBService.postCall($scope.add_user,"/api/users/save")
      .then(function(data){
        if (data.success == true) {
            if(data.new_user) {
              window.location = base_url+"/users/edit/"+data.id;
            } else {
              bootbox.alert(data.message);
            }
        } else {
          bootbox.alert(data.message);
        }
        $scope.processing_req = false;
      });

    }

    $scope.deleteUser = function(user){
      bootbox.confirm("Are you sure?", (result)=>{
        if(result){
          DBService.postCall({
            user_id:user.id
          },
          "/api/users/delete")
          .then(function(data){
            if (data.success) {
              user.inactive = data.inactive;
            }
            bootbox.alert(data.message);
          });
        }
      });
    }

    $scope.getUserAccess = function(){

      if($scope.user_id == 0) return;

      DBService.postCall({
        user_id : $scope.user_id,
        role_id : $scope.add_user.role
      }, "/api/users/access-rights-loc")
      .then(function(data){
        if(data.success){
          $scope.user_access_rights = data.access_rights;
        } else {
          bootbox.alert(data.message);
        }
      });
    }

    $scope.delete_access_location_data = function(location_data){
      if(confirm("Are you sure to delete this access location?")){
        DBService.postCall({
          access_location_id: location_data.id,
          user_id:$scope.user_id
        },"/api/users/access-rights-loc/delete")
        .then(function(data){
          if (data.success){
            $scope.getUserAccess()
          }
        });
      }
    }

    $scope.show_location_access_model = function(id, access_name){

      $scope.access_location_data.access_type_id = access_name.id;
      
      if (id == 1){
        $scope.access_location_data.modal_id = 1;
        $scope.access_location_data.city = true;
        $scope.access_location_data.city_id = -1;
        $scope.submit_access_location_data();

      }

      if (id == 2){
        $scope.access_location_data.modal_id = 2;
        $scope.access_location_data.city = true;
        $scope.access_location_data.center = false;
        $scope.access_location_data.group = false;
        $scope.access_location_data.city_id = 0;
        $("#location_access").modal('show');
      }

      if (id == 3){
        $scope.access_location_data.modal_id = 3;
        $scope.access_location_data.city = true;
        $scope.access_location_data.center = true;
        $scope.access_location_data.group = false;
        $scope.access_location_data.city_id = 0;
        $("#location_access").modal('show');
      }

      if (id == 4){
        $scope.access_location_data.modal_id = 4;
        $scope.access_location_data.center = true;
        $scope.access_location_data.city = true;
        $scope.access_location_data.group = true;
        $scope.access_location_data.city_id = 0;
        $("#location_access").modal('show');
      }      

    }


    $scope.submit_access_location_data = function(){

      $scope.access_location_data.role_id = $scope.add_user.role;
      
      DBService.postCall(
        $scope.access_location_data,
        "/api/users/access-rights-loc/add")
      .then(function(data){
        if (data.success){
          $scope.getUserAccess();
        } else {
          bootbox.alert(data.message);
        }
      });
      
      $scope.hide_location_access_model();
    }

    $scope.clear_center_ids = function(){
      $scope.access_location_data.centers_ids = [];
    }

    $scope.clear_group_ids = function(){
      $scope.access_location_data.groups_ids = [];
    }
    

    $scope.add_center_in_access_location = function(id){

      if ($scope.access_location_data.centers_ids.includes(id)) {
        $scope.access_location_data.centers_ids.splice($scope.access_location_data.centers_ids.indexOf(id),1);
      } else {
        $scope.access_location_data.centers_ids.push(id);
      }
    }

    $scope.add_group_in_access_location = function(id){

      if ($scope.access_location_data.groups_ids.includes(id)) {
        $scope.access_location_data.groups_ids.splice($scope.access_location_data.groups_ids.indexOf(id),1);
      } else {
        $scope.access_location_data.groups_ids.push(id);
      }
 
    }

    $scope.copyToAll = function(access_right_id){
      bootbox.confirm("Are you sure to copy to all other access?", (result)=>{
        if(result){

          DBService.postCall({
            user_id: $scope.user_id,
            access_right_id:access_right_id,
          },
          "/api/users/access-rights-loc/copy")
          .then(function(data){
            if (data.success) {
              $scope.getUserAccess();
            }
            bootbox.alert(data.message);
          });
        }

      });
    }

    $scope.hide_location_access_model = function(){
      $("#location_access").modal('hide');
    }

    $scope.addUserModal = function(){
      $scope.add_user = {};
      $scope.UserForm.$setPristine();
      $scope.getRoles();
      $("#add_user_modal").modal('show');
    }

    $scope.hideModal = function(modal_id){
      $("#"+modal_id).modal('hide');
    }

    $scope.addSports = function(value, var_name){

      var index = $scope.add_user[var_name].indexOf(value);
      if( index > -1 ){
        $scope.add_user[var_name].splice(index, 1);
      } else {
        $scope.add_user[var_name].push(value);
      }
    }

});
