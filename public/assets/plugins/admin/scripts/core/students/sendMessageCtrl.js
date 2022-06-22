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
				$scope.msgData.original_content = data.content;
				$scope.msgData.content = data.content;

				variables = [];
				message_split = data.content.split("{#var#}")
				repeat_no = message_split.length - 1

				variables = []
				for(i =0; i < repeat_no; i++){
				  variables.push({ content : "" })
				}

				$scope.variables = variables;
				
				$scope.setContent();
			}
		});
	}

	$scope.setContent = function(){
		$("#content_msg").html($scope.msgData.content);
	}

	$scope.modifyContent = function(){
		
		message_split = $scope.msgData.original_content.split("{#var#}")
		final_message = message_split[0]
		for(index = 1; index < message_split.length; index++){
			final_message += $scope.variables[index - 1].content ? $scope.variables[index - 1].content : "{#var#}"
			final_message += message_split[index]
		}
		$scope.msgData.content = final_message
		$scope.setContent()
	}

});