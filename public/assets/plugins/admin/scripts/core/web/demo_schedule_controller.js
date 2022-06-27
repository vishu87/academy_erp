app.controller("Demo_controller", function($scope, $http, DBService) {
   
  $scope.processing = false;
  $scope.formData = {};

  $scope.init = function(){
	DBService.getCall("/api/get-state-city-center").then(function(data){
		if (data.success) {
	  		$scope.cities = data.cities;
	  		$scope.centers = data.centers;
	  		$scope.groups = data.groups;
		}else{
		  	bootbox.alert(data.message);	
		}
	});
  }

  $scope.onSubmit = function(){
    $scope.processing = true;
    $scope.formData.type = 'demo-schedule';
  	DBService.postCall($scope.formData,"/api/open-lead/store-lead").then(function(data){
	  	if (data.success) {
        	bootbox.alert(data.message);  
        	$scope.tab = 2;
	  	} else {
	  		bootbox.alert(data.message);	
	  	}
      $scope.processing = false;
	 	});
  }

  $scope.schedule = function(){
  		DBService.getCall("/api/open-lead/get-schedule/"+$scope.formData.group_id).then(function(data){
		  if (data.success) {
        	$scope.visit_dates  = data.visit_dates;
        	$scope.visit_time  = data.visit_time;

		  } else {
		  	bootbox.alert(data.message);	
		  }
		});
  }

});