app.controller('smsTempCtrl',function($scope , $http, $timeout , DBService){

	$scope.formData = {type:1};

	$scope.init = function(){
		DBService.getCall('/communications/sms-template/init').then(function(data){
			$scope.templates = data.templates;
		});
	}

	$scope.add = function(){
		$scope.formData = {type:1};
		$("#add-template").modal("show");
	}

	$scope.edit = function(template){
		$scope.formData = JSON.parse(JSON.stringify(template));
		$("#add-template").modal("show");
	}

	$scope.onSubmit = function() {
		$scope.processing = true;
		DBService.postCall($scope.formData,'/communications/sms-template/store').then(function(data){
			if(data.success){
				if($scope.formData.id){
					for (var i = $scope.templates.length - 1; i >= 0; i--) {
						if($scope.templates[i].id == data.template.id){
							$scope.templates[i] = data.template;
						}
					}
				}else{
					$scope.templates.push(data.template);
				}

				$scope.formData = {};
				$scope.addForm.$setPristine();
				$("#add-template").modal("hide");
			}
			alert(data.message);
			$scope.processing = false;;
		});
	}


	$scope.delete  = function(template,index){
		if(confirm("Are you sure")){
			DBService.getCall('/communications/sms-template/delete/'+template.id).then(function(data){
				if(data.success){
					alert(data.message);
					$scope.templates.splice(index,1);
				}else{
					alert(data.message);
				}
			});
		}
	}


});