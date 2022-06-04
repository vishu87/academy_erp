app.controller('SignUp_controller',function($scope , $http, $timeout , DBService){
	
    $scope.tab = 1;
    $scope.search = false;

    $scope.submitForm = function(){
		$scope.processing = true;
		$scope.false = true;
		DBService.postCall({email:$scope.email},'/api/sign-up').then(function(data){
			$scope.found = data.found;
			$scope.processing = false;
			$scope.search = true;
		});
	}

});
