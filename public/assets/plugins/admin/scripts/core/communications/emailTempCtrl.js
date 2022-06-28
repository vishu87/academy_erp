app.controller('emailTempCtrl',function($scope , $http, $timeout , DBService){

	$scope.formData = {};

	$scope.init = function(){
		DBService.getCall('/api/communications/email-template/init').then(function(data){
			$scope.templates = data.templates;
		});
	}

	$scope.add = function(){
		$scope.formData = {};
		$("#add-template").modal("show");
	}

	$scope.onSubmit = function() {
		$scope.processing = true;
		DBService.postCall($scope.formData,'/api/communications/email-template/store').then(function(data){
			if(data.success){
				$("#add-template").modal("hide");
				bootbox.alert(data.message);
				$scope.init();
			} else {
				bootbox.alert(data.message);
			}
			$scope.processing = false;
		});
	}

	$scope.edit = function(template){
		$scope.formData = JSON.parse(JSON.stringify(template));
		$("#add-template").modal("show");
	}

	$scope.delete  = function(template_id,index){
		bootbox.confirm("Are you sure?", (check)=>{
      	if (check) {
			DBService.getCall('/api/communications/email-template/delete/'+template_id).then(function(data){
				if(data.success){
					$scope.templates.splice(index,1);
					bootbox.alert(data.message);
				}else{
					bootbox.alert(data.message);
				}
			});
		}
		});
		
	}


});