app.controller("Lead_controller", function($scope, $http, DBService, Upload) {
  
  $scope.tab = 1;

  $scope.formData = {};
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

  $scope.uploadDocument = function (file) {
    var url = base_url+'/uploads/file';
        Upload.upload({
            url: url,
            data: {
              file: file
            }
        }).then(function (resp) {
            if(resp.data.success){
              $scope.formData.document_url = resp.data.url;
              $scope.formData.document = resp.data.path;
            } else {
              alert(resp.data.message);
            }
            $scope.uploading_file = false;
        }, function (resp) {
            $scope.uploading_file = false;
        }, function (evt) {
            $scope.uploading_percentage = parseInt(100.0 * evt.loaded / evt.total) + '%';
        });
    }

  $scope.onSubmit = function(){
    $scope.processing = true;
  	DBService.postCall($scope.formData,"/api/registrations/store-lead").then(function(data){
	  	if (data.success) {
        $scope.tab = 2;
	  	} else {
	  		bootbox.alert(data.message);	
	  	}
      $scope.processing = false;
	 });
  }


});