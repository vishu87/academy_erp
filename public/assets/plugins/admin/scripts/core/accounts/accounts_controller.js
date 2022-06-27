app.controller("AcountsController", function($scope, $http, DBService, Upload) {
	$scope.states = {};
	$scope.gst = {};
	$scope.listData = {};
	$scope.editForm = false;

	$scope.list = function(){
		DBService.postCall({},"/api/accounts/list")
		.then(function(data){
		  $scope.listData = data;
		});	
	}

	$scope.init = function(tag){
	  DBService.postCall({Tag:tag},"/api/get-state-city-center-data")
	  .then(function(data){
	      $scope.states = data.state;
	  });
	  $scope.list();
	}

	$scope.submit = function(gst_data){
		gst_data.id = 0;
		$scope.processing = true;
		DBService.postCall(gst_data, "/api/accounts/save").then(function(data){
			if (data.success) {
				bootbox.alert(data.message);
				$scope.gst = {};
				$("#contact_modal").modal("hide");
				$scope.list();
			}else{
				bootbox.alert(data.message);
			}
			$scope.processing = false;
		});
	}

	$scope.edit = function(gst_data){
		console.log(gst_data);
		$scope.editForm = true;
		$("#contact_modal").modal("show");
		$scope.gst = gst_data;
		if (!$scope.gst.show) {
			$scope.gst.show = true;
		}
	}

	$scope.update = function(gst_data){

		$scope.processing = true;

		DBService.postCall(gst_data, "/api/accounts/save").then(function(data){
			if (data.success) {
				bootbox.alert(data.message);
				$scope.gst = {};
				$scope.editForm = false;
				$("#contact_modal").modal("hide");
				$scope.list();
			} else {
				bootbox.alert(data.message);
			}
			$scope.processing = false;
		});
	}

	$scope.delete = function(id){
		bootbox.confirm("Are you sure?", (result)=>{
			if (result) {
				DBService.postCall({id:id},'/api/accounts/delete')
				.then(function(data){
					if (data.success) {
						bootbox.alert(data.message);
						$scope.list();
					}else{
						bootbox.alert(data.message);
					}
				});
			}
		})
	}

	$scope.hideModal = function(modal_id){
		$("#"+modal_id).modal("hide");
	}

	$scope.addAcount = function(){
		$scope.gst = {};
		$("#contact_modal").modal("show");
	}

	$scope.uploadGstLogo = function (file) {
		$scope.logoProcessing = true;
		if(file){
			$scope.uploading = true;
			var url = base_url+'/api/upload/photo';
	        Upload.upload({
	            url: url,
	            data: {
	            	photo: file,
	            	resize: 1,
	              	crop: 0,
	              	width: 720,
	              	thumb: 1
	            }
	        }).then(function (resp) {
	            if(resp.data.success){
	            	$scope.gst.logo = resp.data.path;
	            } else {
	            	alert(resp.data.message);
	            }
	            $scope.uploading = false;
	        });
		}
		$scope.logoProcessing = false;
    }

    $scope.removeLogo = function(){
    	$scope.gst.logo = '';
    }
});
