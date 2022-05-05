app.controller('events_controller', function($scope , $http, $timeout ,Upload, DBService){
	
	$scope.formData = {gallery:[]};
	$scope.processing = false;
	$scope.filter = {};
	$scope.loading = false;
	$scope.id = 0;

	$scope.getList = function(){
		$scope.loading = true;
		DBService.postCall($scope.filter,'/api/events/getList').then(function(data){
			$scope.events = data.events;
			$scope.loading = false;
		});
	}

	$scope.init = function(id){
		$scope.id = id;
		$scope.loading = true;
		$scope.formData = {hidden:0,pay_later:0,registration_closed:0};
		DBService.postCall({id:$scope.id},'/api/events/init').then(function(data){
			if(data.formData){
				$scope.formData = data.formData;
			}
			$scope.cities = data.cities;
			$scope.loading = false;
		});
	}

	$scope.showEvent = function(event){
		$scope.open_event = event;
		$("#showEvent").modal("show");
	}

	$scope.uploadFile = function (file, name, object) {
		object.uploading = true;
		var url = base_url+'/api/events/uploadFile';
        Upload.upload({
            url: url,
            data: {
            	media: file,
            	name : name
            }
        }).then(function (resp) {
        	console.log(resp);
            if(resp.data.success){
            	object[name] = resp.data.media;
            } else {
            	alert(resp.data.message);
            }
            object.uploading = false;
        }, function (resp) {
            console.log('Error status: ' + resp.status);
            object.uploading = false;
        }, function (evt) {
            // $scope.uploading_percentage = parseInt(100.0 * evt.loaded / evt.total) + '%';
        });
    }

    $scope.removeFile = function(name){
    	$scope.formData[name] = '';
    	console.log($scope.formData);
    }

    $scope.uploadGalaryFile = function (files, name) {
		$scope.uploading = true;
		var url = base_url+'/api/events/upload-galary-image';
		var count = 1;

		angular.forEach(files , function(file){

	        Upload.upload({
	            url: url,
	            data: {
	            	media: file
	            }
	        }).then(function (resp) {
	        	console.log(resp.data);
	            if(resp.data.success){
	            	$scope.formData.gallery.push(resp.data);

	            } else {
	            	alert(resp.data.message);
	            }
	            $scope.uploading = false;
	        }, function (resp) {
	            // console.log('Error status: ' + resp.status);
	            $scope.uploading = false;
	        }, function (evt) {
	            $scope.progress = parseInt(100.0 * evt.loaded / evt.total) + '%';
	        });
	        count++;
			
		});
		
		

    }

    $scope.removeGalleryImage = function(index){
    	$scope.formData.gallery.splice(index,1);
    }

	$scope.addEvent = function(){
		$scope.formData.id = $scope.id;
		$scope.processing = true;
		DBService.postCall($scope.formData,'/api/events/add').then(function(data){
			if(data.success){
				$scope.formData = {};
				alert(data.message);
			}else{	
				alert(data.message);
			}
			$scope.processing = false;
		});
	}
});