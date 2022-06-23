app.controller('SignUp_controller',function($scope , $http, $timeout , DBService){
    $scope.tab = 1;
    $scope.search = false;
    $scope.formData = {};

    $scope.onSubmit = function(valid){
		$scope.processing = true;
		$scope.false = true;
		DBService.postCall({email:$scope.formData.email},'/api/sign-up/search').then(function(data){
			if(data.success){
				$scope.tab = 2;
				$scope.message = data.message;
				$("#success_message").html(data.message);
			} else {
				bootbox.alert(data.message);
			}
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
