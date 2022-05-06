app.controller('CenterController',function($scope , $http, $timeout , DBService, Upload){
	$scope.center = {};
	$scope.centers = {};
	$scope.cities = [];
	$scope.contact_person = {};
	$scope.operation_timing = {'day':''};
	$scope.members = [];
	$scope.timing = {'update':false,day:0};
	$scope.loading = true;
	$scope.year = {};
	$scope.months = {};
	$scope.quaters = {};
	$scope.group = {};
	$scope.cetegories = {};
	$scope.edit_group_id = null;
	$scope.update = true;
	$scope.city_id = 0;
	$scope.processing = false;
	$scope.imageProcessing = false;
	$scope.path = {};
	
	$scope.cityList = function(){
		DBService.getCall("/api/get-city-list")
		.then(function(data){
			if (data.success) {
				$scope.cities = data.data;
			}
		})
	}

	$scope.centerList = function(){
		DBService.postCall({city_id:$scope.city_id},"/api/centers/list")
		.then(function(data){
			if (data.success) {
				$scope.centers = data.data
			}
		})
		$scope.cityList();
	}


   	$scope.addCenter = function(){
	    $("#add_center_modal").modal("show");
   	}

	$scope.createNewCenter = function(){
		$scope.processing = true;
		DBService.postCall($scope.center,'/api/centers/add')
		.then(function(data){
			if (data.success) {
				window.location = base_url+"/centers/edit/"+data.id;
			} else {
				bootbox.alert(data.message);
			}
			$scope.processing = false;
		});
	}

	$scope.init = function(id){
		$scope.loading = true;
		DBService.postCall({center_id:id},"/api/centers/edit")
		.then(function(data){
			if(data.success){
				$scope.center = data.center;
				$scope.fetchCenterImages();
				$scope.fetchCenterGroups();
				$scope.params();
			} else {
				bootbox.alert(data.message);
			}
			$scope.loading = false;
		});
	}

	$scope.params = function(){
		DBService.getCall("/api/centers/params")
		.then(function(data){
			if (data.success) {
				$scope.cities = data.cities;
				$scope.cordinators = data.cordinators;
				$scope.days = data.days;
				$scope.categories = data.categories;
				$scope.even_categories = data.even_categories;

			}
		})
	}



	$scope.fetchCenterImages = function(){
		DBService.getCall('/api/centers/images/'+$scope.center.id).then(function(data){
			$scope.center.images = data.images;
		});
	}

	$scope.fetchCenterGroups = function(){
		DBService.getCall('/api/centers/groups/'+$scope.center.id).then(function(data){
			$scope.center.groups = data.groups;
		});
	}	

	$scope.onSubmit = function(){
		$scope.processing = true;
		DBService.postCall($scope.center,'/api/centers/update').then(function(data){
			bootbox.alert(data.message);
			$scope.processing = false;
		});
	}

	$scope.uploadCenterImage = function (file, object) {

		$scope.imageProcessing = true;
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
	            	$scope.saveImage(resp.data.path);
	            } else {
	            	alert(resp.data.message);
	            }
	            $scope.uploading = false;
	        }, function (resp) {
	            console.log('Error status: ' + resp.status);
	            $scope.uploading = false;
	        }, function (evt) {
	            $scope.uploading_percentage = parseInt(100.0 * evt.loaded / evt.total) + '%';
	        });
		}
		$scope.imageProcessing = false;
    }

    $scope.saveImage = function(path){
    	$scope.path.center_id = $scope.center.id;
    	$scope.path.path_url = path;
    	DBService.postCall($scope.path,'/api/centers/save-image').then(function(data){
    		if(data.success){
    			bootbox.alert(data.message);
    			$scope.fetchCenterImages();
    		}else{
    			bootbox.alert(data.message);
    		}
    	});

    }

    $scope.removeImage = function(image,index){
    	bootbox.confirm("Are you sure?", (check)=>{
      	if (check) {
	    	DBService.postCall(image,'/api/centers/remove-image').then(function(data){
	    		if(data.success){
	    			bootbox.alert(data.message);
	    			$scope.center.images.splice(index,1);
	    		}else{
	    			bootbox.alert(data.message);
	    		}
	    		image.processing  = false;
	    	});
    	}
      	});
    }


	$scope.loadTargets1 = function(){
		$scope.loading_target = true;
		DBService.postCall({year_id:$scope.year.year_id , center_id:$scope.center.id},'/api/center/loadTargets').then(function(data){
			if(data.year){

				$scope.year = data.year;
			}else{
				$scope.year = {year_id:$scope.year.year_id};
			}
			if(data.quaters){

				$scope.quaters = data.quaters;
			}else{
				$scope.quaters = {};
			}

			if(data.months){

				$scope.months = data.months;
			}else{
				$scope.months = {};
			}
			$scope.loading_target = false;
		});
	}

	$scope.addContactPersonData1  = function(){
    	DBService.postCall({data:$scope.center},'/api/centers/add-contact-person')
    	.then(function(data){
			if (data.success) {
				$("#contact_modal").modal("hide");
			}
		});
	}

	$scope.addPersonToList1 = function(){
		if($scope.contact_person.member_name){
			
			$scope.center.contact_persons.push(JSON.parse(JSON.stringify($scope.contact_person)));
		}
		$scope.addContactPersonData();
		$scope.contact_person = {};
	}

	$scope.removePerson1 = function(index){
		if (confirm("Are you sure")) {
			$scope.center.contact_persons.splice(index,1);
			$scope.addContactPersonData();
		}
	}

	$scope.addTimingInList = function(){
		$scope.timmingProcessing = true;
		$scope.timing.center_id = $scope.center.id;
		DBService.postCall($scope.timing,'/api/centers/addGroupTiming').then(function(data){
			if(data.success){
				// $scope.timing = {};
				// $scope.timing.update = false;
				bootbox.alert(data.message);
				$("#training_day_modal").modal('hide');
				$scope.fetchCenterGroups();
			}else{
				bootbox.alert(data.message);
			}
			
			$scope.timmingProcessing = false;
		});
	}

	$scope.editTiming = function(timing){
		timing.update = true;
		$scope.timing = {};
		$scope.timing = JSON.parse(JSON.stringify(timing));
		$("#training_day_modal").modal("show");

	}

	$scope.cancelTimingEdit = function(){
		$scope.timing = {'update':false,group_id:0,day:0};
	}

	$scope.removeTiming = function(obj){
		$("#remove-timing").modal("show");
		$scope.open_timing = obj;
	}

	$scope.submitRemoveTiming = function(){
		$scope.open_timing.processing = true;
		DBService.postCall({timing:$scope.open_timing},'/api/centers/deleteTiming').then(function(data){
			if(data.success){
				$scope.center.groups = data.center.groups;
				$scope.open_timing = {};
				$("#remove-timing").modal("hide");

			}else{

				bootbox.alert(data.message);
			}
			$scope.open_timing.processing = false;
		});
	}

	$scope.deleteCenter  = function(id, $index){
		bootbox.confirm("Are you sure?", (check)=>{
      	if (check) {
			DBService.getCall("/api/centers/delete-center/"+id).then(function(data){
				if (data.success) {
					bootbox.alert(data.message);
					$scope.centers.splice($index,1);
				}else{
					bootbox.alert(data.message);
				}
			});
		}
		});
	}

	$scope.addGroup = function(){
		$scope.group = {};
	    $("#group_modal").modal("show");
   	}	

	$scope.updateGroup = function(group_id){
		DBService.getCall('/api/centers/edit-group/'+group_id).then(function(data){
			$scope.group = data.group;
		});
	    $("#group_modal").modal("show");
	}

	$scope.onSubmit_group = function(){
		$scope.groupProcessing = true;
		$scope.group.center_id = $scope.center.id;
		DBService.postCall($scope.group,'/api/centers/add-group').then(function(data){
			if(data.success){
				$('#group_modal').modal('hide');
				$scope.fetchCenterGroups();
			}else{
				bootbox.alert(data.message);
			}
			$scope.groupProcessing = false;
		});
	}

	$scope.deleteGroup = function(id){
		DBService.postCall({id:id},'/api/groups/delete')
		.then(function(data){
			if (data.success) {
				bootbox.alert(data.message);
				$scope.init($scope.center.id);	
			}else{
				bootbox.alert(data.message);
			}
		})
	}

   	$scope.addTrainingDay = function(group_id){
   		$scope.timing = {'update':false,group_id:group_id,day:0};
	    $("#training_day_modal").modal("show");
   	}



	$scope.viewCurriculum1 = function(group_id){
		$("#viewCurriculum").modal("show");
		$scope.group_events= [];
		DBService.postCall({group_id:group_id},'/api/centers/group/schedule')
		.then(function(data){
			if(data.success){
				// console.log(data.events);
				$scope.group_events = data.events;
			}
			$scope.processing = false;
		});
	}

   	$scope.addContactPerson1 = function(){
	    $("#contact_modal").modal("show");
   	}

   	$scope.hideModal1 = function(modal_id){
	    $("#"+modal_id).modal("hide");
   }
});