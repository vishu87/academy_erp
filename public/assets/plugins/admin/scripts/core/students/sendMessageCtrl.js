app.controller('sendMessageCtrl', function($scope,$rootScope,DBService){

	$scope.variables = [];

	$scope.init = function(){
		DBService.postCall({api_key:api_key, only_active : $scope.filter.only_active},'/api/communications/send-message/init').then(function(data){
			if(data.success){
				$scope.sms_templates = data.sms_templates;
				$scope.email_templates = data.email_templates;
			}
		});
	}

	$scope.init();

	$scope.templateChange = function(){
		DBService.postCall( $scope.msgData ,'/api/communications/send-message/get-content').then(function(data){
			if(data.success){
				$scope.msgData.content = data.content;

				variables = [];

				message_split = message.split("{#var#}")
				repeat_no = message_split.length - 1

				variables = []
				for(i =0; i < repeat_no; i++){
				  variables.push({ content : i+"" })
				}
				
				$scope.setContent();
			}
		});
	}

	$scope.setContent = function(){
		$("#content_msg").html($scope.msgData.content);
	}

});