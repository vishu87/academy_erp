app.controller('SignUp_controller',function($scope , $http, $timeout , DBService){
	
    $scope.tab = 1;
    $scope.search = false;
    $scope.formData = {};

    $scope.submitForm = function(){
		$scope.processing = true;
		$scope.false = true;
		DBService.postCall({email:$scope.email},'/api/sign-up').then(function(data){
			$scope.found = data.found;
			$scope.processing = false;
			$scope.search = true;
		});
	}


	$scope.forgetPassword = function(){
		$scope.processing = true;
		$scope.false = true;
		DBService.postCall($scope.formData,'/forget-password').then(function(data){
			if(data.success){
				bootbox.alert(data.message);
				$scope.found = data.found;
			} else {
				bootbox.alert(data.message);
			}
			$scope.processing = false;
			$scope.search = true;
		});
	}

});
