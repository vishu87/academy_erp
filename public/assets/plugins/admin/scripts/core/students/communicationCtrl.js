app.controller('communicationCtrl', function($scope,$rootScope,DBService){
	$scope.max = 100;
	$scope.pn = 1;
	$scope.total_pn = 0;
	$scope.count = 0;
	$scope.checkAll = false;
	$scope.show_list = true;
	$scope.show_removed_list = true;
	$scope.send_type_check = 1;
	$scope.removed_students = [];
	$scope.formData = {demo_check:false};
	$scope.filter = {
		only_active: 1,
		cities: [],
		centers: [],
		groups: [],
		status: [],
		categories: [],
		batch_types: []
	};
	$scope.template_lists = [];

	$scope.popTemplateList = function(){
		$scope.template_lists = [];
		for (var i = 0; i < $scope.templates.length; i++) {
			if ($scope.templates[i].type == $scope.formData.sms_type) {
				$scope.template_lists.push($scope.templates[i]);
			}
		}
	}

	$scope.changeType = function(type){
		if(type == 1){
			$scope.send_type_check = 1;
			$scope.formData.send_type = 1;
		} if(type == 2){
			$scope.send_type_check = 2;
			$scope.formData.send_type = 2;
		}
	} 

	$scope.check_all = function(prop){

		if(prop == 1){
			if($scope.check_all_city){
				$scope.filter.cities = [];
				for (var i = $scope.cities.length - 1; i >= 0; i--) {
					$scope.filter.cities.push($scope.cities[i].value);
				}
			}else{
				$scope.filter.cities = [];

			}
			$scope.showFilterCenters();
		}

		if(prop == 2){
			if($scope.check_all_center){
				$scope.filter.centers = [];
				for (var i = $scope.filter_centers.length - 1; i >= 0; i--) {
					$scope.filter.centers.push($scope.filter_centers[i].value);
				}
			}else{
				$scope.filter.centers = [];

			}
			$scope.showFilterGroups();
		}

		if(prop == 3){
			if($scope.check_all_groups){
				$scope.filter.groups = [];
				for (var i = $scope.filter_groups.length - 1; i >= 0; i--) {
					$scope.filter.groups.push($scope.filter_groups[i].value);
				}
			}else{
				$scope.filter.groups = [];

			}
		}

		$scope.getStudents();
	}

	$scope.init = function(){
		DBService.postCall({api_key:api_key, only_active : $scope.filter.only_active},'/api/communications/send-message/init').then(function(data){
			if(data.success){
				$scope.sms_templates = data.sms_templates;
				$scope.email_templates = data.email_templates;

				$scope.getStateCityCenter();
			}
		});
	}

	$scope.getStateCityCenter = function(){
	    DBService.postCall({Tag:'comm_user'},"/api/get-locations")
	    .then(function (data){
	      	if(data.success) {
	        	$scope.cities = data.city;
	        	$scope.centers = data.center;
	        	$scope.groups = data.group;
	      	}
	    });
	 }


	$scope.activeCenter = function(active){
		$scope.filter.only_active = active;
		$scope.init(); 
	}

	$scope.listing = function(){
		DBService.postCall({api_key:api_key},'/api/communications/send-message/listing').then(function(data){
			if(data.success){
				$scope.communications = data.communications;
				$scope.count = data.count;
			}
		});
	}

	$scope.viewStudetns = function(comm){
		$scope.comm_pn = 1;
		$scope.total_comm_pn = 0;
		$scope.open_comm =comm;
		$("#students").modal("show");
		
		$scope.commStudents(1);
	}

	$scope.nextPageComm = function(){
		if(($scope.comm_pn+1)*$scope.max <= ($scope.count+$scope.max)) {
			$scope.commStudents($scope.comm_pn + 1);
		}
	}

	$scope.prevPageComm = function(){
		if($scope.comm_pn - 1 > 0){
			$scope.commStudents($scope.comm_pn - 1);
		}
	}

	$scope.commStudents = function(page_number){
		$scope.open_comm.pn = page_number;
		$scope.comm_pn = page_number;
		DBService.postCall($scope.open_comm,'/api/communications/send-message/comm_students').then(function(data){
			$scope.open_comm.students = data.students;
			$scope.loading_students = false;
			$scope.total_comm_pn = data.total_pn;
			$scope.count = data.count;
		});
	}

	$scope.addFilter = function(type,value){

		var idx = $scope.filter[type].indexOf(value);
		
		if(idx == -1){
			$scope.filter[type].push(value);
		} else {
			$scope.filter[type].splice(idx,1);
		}

		if(type == "cities"){
			$scope.showFilterCenters();
		}

		if(type == "centers"){
			$scope.showFilterGroups();
		}

		$scope.getStudents();

	}

	$scope.showFilterCenters = function(){
		$scope.filter_centers = [];

		for (var i = 0; i < $scope.centers.length; i++) {
			if($scope.filter.cities.indexOf($scope.centers[i].city_id) != -1){
				$scope.filter_centers.push($scope.centers[i]);
			} else {
				var idx = $scope.filter.centers.indexOf($scope.centers[i].id);
				if(idx != -1){
					$scope.filter.centers.splice(idx,1);
				}
			}
		}
	}

	$scope.showFilterGroups = function(){
		$scope.filter_groups = [];

		for (var i = 0; i < $scope.groups.length; i++) {
			if($scope.filter.centers.indexOf($scope.groups[i].center_id) != -1){
				$scope.filter_groups.push($scope.groups[i]);
			} else {
				var idx = $scope.filter.groups.indexOf($scope.groups[i].id);
				if(idx != -1){
					$scope.filter.groups.splice(idx,1);
				}
			}
		}
	}

	$scope.getStudents = function(page_number=1){
		$scope.filter.pn = page_number;
		$scope.pn = page_number;
		$scope.filter.removed_students = $scope.removed_students;
		DBService.postCall($scope.filter,'/api/communications/send-message/getStudents').then(function(data){
			if(data.success){

				$scope.students = data.students;
				$scope.student_ids = data.student_ids;
				if(data.students.length > 0){
					$scope.checkAll = true;
				}

			}
			$scope.loading = false;
			$scope.count = data.count;
			$scope.total_pn = data.total_pn;

		});
	}

	$scope.nextPage = function(){
		if(($scope.pn+1)*$scope.max <= ($scope.count+$scope.max)) {
			$scope.getStudents($scope.pn + 1);
		}
	}

	$scope.prevPage = function(){
		if($scope.pn - 1 > 0){
			$scope.getStudents($scope.pn - 1);
		}
	}

	$scope.showNumber = function(number){

		$("#showNumber").modal("show");
		$scope.mobile_show = number;
	}

	$scope.sortBy = function(type){
		if($scope.filter.sort_by == type){
			if($scope.filter.sorting == "ASC"){
				$scope.filter.sorting = "DESC";
			} else if($scope.filter.sorting == ""){
				$scope.filter.sorting = "ASC";
			} else {
				$scope.filter.sorting = "";
			}
		} else {
			$scope.filter.sort_by = type;
			$scope.filter.sorting = "ASC";
		}

		$scope.sort_by = $scope.filter.sort_by;
		$scope.sorting = $scope.filter.sorting;

		$scope.getStudents(1);
	}

	$scope.sendMessage = function(){
		$scope.formData = {};
		$("#messageForm").modal("show");

	}

	$scope.toggleList = function(){
		$scope.show_list = !$scope.show_list;
	}

	$scope.toggleRemovedList = function(){
		$scope.show_removed_list = !$scope.show_removed_list;
	}

	$scope.postMessage = function(){
		$scope.processing = true;
		$scope.formData.student_ids = $scope.student_ids;
		$scope.formData.removed_students = $scope.removed_students;
		DBService.postCall($scope.formData,'/api/communications/send-message/postMessage').then(function(data){
			if(data.success){

				if(!data.demo_check){

					$scope.filter = {
						cities: [],
						centers: [],
						groups: [],
						status: [],
						
					};
					$scope.formData = {};
					$scope.removed_students = [];
					$scope.students = [];
					$scope.pn = 1;
					$scope.total_pn = 0;
					$scope.count = 0;
					$("#messageForm").modal("hide");
				}

			}
			$scope.processing =false;

			alert(data.message);
		});
	}

	$scope.removeStudent = function(student,index){
		$scope.removed_students.push(student);
		$scope.students.splice(index,1);
		$scope.count--;
	}

	$scope.addStudentToList = function(student,index){
		$scope.students.push(student);
		$scope.removed_students.splice(index,1);
		$scope.count++;
	}
	

});