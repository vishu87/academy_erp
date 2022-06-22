app.controller('communicationListCtrl', function($scope,$rootScope,DBService){

	$scope.max = 50;
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

	$scope.listing = function(){
		DBService.postCall({},'/api/communications/send-message/listing').then(function(data){
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

});