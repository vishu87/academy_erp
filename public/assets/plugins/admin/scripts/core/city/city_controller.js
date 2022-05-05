app.controller("CityController", function($scope, $http, DBService) {
  $scope.state_data = {};
  $scope.city_data = {};
  $scope.add_city = {};
  $scope.city_list = {};

  $scope.cityInit = function(){
    DBService.getCall("/api/states")
    .then(function(data){
      if (data.success) {
        $scope.state_data = data.states;
        $scope.getCityList();
      }
    });
   }

   $scope.getCityList = function(){
    DBService.postCall({},"/api/city/list")
    .then(function(data){
      $scope.city_list = data.city_list;
    });
   }

   $scope.addCity = function(){
      $scope.add_city = {};
      $("#city_modal").modal("show");
   }

   $scope.selectCity = function(state_id){
      DBService.getCall("/api/cities/"+state_id)
      .then(function(data){
        if (data.success) {
          $scope.city_data = data.cities;
        }
      });
    }

   $scope.hideModal = function(){
      $("#city_modal").modal("hide");
   }

   $scope.putCityName = function(){
    var city_name = "";
    for (var i = 0; i < $scope.city_data.length; i++) {
      if($scope.city_data[i].value == $scope.add_city.base_city_id){
        city_name = $scope.city_data[i].label;
      }
    }
    $scope.add_city.city_name = city_name;
   }

   $scope.saveCity = function(){
    $scope.processing_req = true;
    DBService.postCall($scope.add_city,"/api/city/save")
    .then(function(data){
      if (data.success) {
        $scope.add_city = {};
        $scope.getCityList();
        $("#city_modal").modal("hide");
      }
      bootbox.alert(data.message);
      $scope.processing_req = false;
    });
   }

   $scope.editCity = function(data){
    $scope.add_city = data;
    $scope.selectCity(data.state_id);
    $("#city_modal").modal("show");
   }

   $scope.deleteCity = function(id){
    bootbox.confirm("Are you sure?", (check)=>{
      if(check){
        DBService.postCall({id:id},"/api/city/delete")
        .then(function(data){
          if (data.success) {
            $scope.getCityList();
          }
          bootbox.alert(data.message);
        });
      }
    });
   }


});
