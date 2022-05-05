app.controller("ClientsController", function($scope, $http, DBService) {
	$scope.addClinet = {}; 
	$scope.clientsRecord = {};
	$scope.update = false;
	
	$scope.init = function(){
		DBService.postCall({},"/api/clients/list")
		.then(function(data)	{
			$scope.clientsRecord = data.list;
		});
	}

	$scope.submit = function(client){
		client.id = 0;
		DBService.postCall({client:client},"/api/clients/save")
		.then(function(data){
			if (data.success) {
				$scope.init();
				alert(data.message);
				$scope.addClinet = {}; 
			}else{
				alert(data.message);
			}
		});
	}

	$scope.edit = function(client){
		$scope.update = true;
		$scope.addClinet = client; 
	}

	$scope.updateData = function(client){
		DBService.postCall({client:client},"/api/clients/save")
		.then(function(data){
			if (data.success) {
				$scope.update = false;
				$scope.init();
				alert(data.message);
				$scope.addClinet = {}; 
			}else{
				alert(data.message);
			}
		});
	}

	$scope.delete = function(id){
		if (confirm("are you sure")) {
			DBService.postCall({id:id},"/api/clients/delete")
			.then(function(data){
				if (data.success) {
					$scope.init();
					alert(data.message);
				}else{
					alert(data.message);
				}
			});
		}
	}

});
