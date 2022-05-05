app.controller("Reg_controller", function($scope, $http, DBService) {
   
   $scope.processing = false;
  	$scope.formData = {};
  	$scope.tab = 1;
  	
  	$scope.init = function(){
		DBService.getCall("/api/get-state-city-center").then(function(data){
		  	if (data.success) {
		  		$scope.cities = data.cities;
		  		$scope.centers = data.centers;
		  		$scope.groups = data.groups;
		  		$scope.states = data.states;
		  	}else{
		  		bootbox.alert(data.message);	
		  	}
		});
  	}

  	$scope.getPaymentOptions = function(){
		DBService.postCall({group_id: 13},"/api/subscriptions/get-payment-options").then(function(data){
		  	$scope.payment_options = data.payment_options;
		  	$scope.getPaymentItems();
		});
  	}
  	$scope.getPaymentOptions();

  	$scope.getPaymentItems = function(){
		DBService.postCall({ categories : $scope.payment_options, group_id: 13 },"/api/subscriptions/get-payment-items").then(function(data){
		  	$scope.payment_items = data.payment_items;
		});
  	}


  	$scope.submit_form = function(){
	  	DBService.postCall($scope.formData,"/api/registrations/store").then(function(data){
		  	if (data.success) {
		  		bootbox.alert(data.message);
		  	} else {
		  		bootbox.alert(data.message);	
		  	}
		});
  	}

  	$scope.showSizeChart = function(){
  		$("#kit_size").modal('show');
  	}

});