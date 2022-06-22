app.controller("ClientsController", function($scope, $http, DBService) {
	$scope.addClinet = {}; 
	$scope.clientsRecord = {};
	$scope.update = false;
	
	$scope.init = function(){
		DBService.postCall({},"/api/clients/list").then(function(data){
			$scope.clientsRecord = data.list;
		});
	}

	$scope.addClient = function(){
		$("#client_modal").modal('show');
	}

	$scope.submit = function(){
		$scope.processing = true;
		DBService.postCall($scope.formData,"/api/clients/save").then(function(data){
			if (data.success) {
				$scope.init();
				$("#client_modal").modal('hide');
				bootbox.alert(data.message);
			}else{
				bootbox.alert(data.message);
			}
			$scope.processing = false;
		});
	}

	$scope.edit = function(client){
		$scope.formData = JSON.parse(JSON.stringify(client));
		$("#client_modal").modal('show');
	}


	$scope.delete = function(id, index){
	bootbox.confirm("Are you sure?", (check)=>{
	      if(check){
				DBService.getCall("/api/clients/delete/"+id).then(function(data){
					if(data.success){
						$scope.clientsRecord.splice(index,1);
						bootbox.alert(data.message);
					}
				});
			}
		});
	}

});
