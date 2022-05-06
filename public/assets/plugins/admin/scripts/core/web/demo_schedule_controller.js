app.controller("Demo_controller", function($scope, $http, DBService) {
   
  $scope.processing = false;
  $scope.formData = {};

  $scope.init = function(){
	DBService.getCall("/api/get-state-city-center").then(function(data){
		  if (data.success) {
		  	$scope.cities = data.cities;
		  }else{
		  		bootbox.alert(data.message);	
		  }
		});
  }

  $scope.onSubmit = function(){
  	$scope.formData.payment_items = $scope.payment_items;
	  DBService.postCall($scope.formData,"/api/registrations/store-demo").then(function(data){
		  if (data.success) {
        bootbox.alert(data.message);  
		  } else {
		  	bootbox.alert(data.message);	
		  }
	});
  }
});