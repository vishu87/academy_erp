var init_filters = {
	page_no : 1,
    max_per_page : 50,
    max_page: 1,
    order_by: '',
    order_type: 'ASC',
    export: false,
    show: false,
	cities: [],
	centers: [],
	status: [],
	sources: [],
	sub_sources: [],
	sub_sources: [],
	lead_for: [],
	action_date_start: "",
	action_date_end: "",
	create_start: "",
	create_end: "",
	order_by: '',
	order_type: 'ASC'
};

app.controller('leads_controller', function($scope,$http,DBService,Upload){
	
	$scope.leadData = {status:1};
	$scope.formData = {};
	$scope.dataset = [];
	$scope.parameters = [];
	$scope.loading = true;

	$scope.noteData = {};

	$scope.filter = init_filters;

	$scope.selectedLeads = [];

	$scope.init = function(){
		$scope.getList();
		$scope.getParams();
	}

	$scope.getList = function(){
		$scope.processing = true;
		DBService.postCall( $scope.filter ,'/api/leads').then(function(data){
			if(data.success){
				$scope.dataset = data.leads;
				$scope.total = data.total;
          		$scope.filter.max_page = Math.ceil($scope.total/$scope.filter.max_per_page)
			}
			$scope.loading = false;
			$scope.processing = false;
			$scope.hide_reset = false;
		});
	}

	$scope.searchList = function(){
	    $scope.filter.page_no = 1;
	    $scope.getList();
	}

	$scope.getParams = function(){
		DBService.getCall('/api/leads/params').then(function(data){
			if(data.success){
				$scope.parameters = data;
				$scope.getGeo();
			}
		});
	}

	$scope.getGeo = function(){
		DBService.postCall({ Tag : 'lead_op' },'/api/get-state-city-center-data').then(function(data){
			if(data.success){
				$scope.parameters.state = data.state;
				$scope.parameters.city = data.city;
				$scope.parameters.center = data.center;
				$scope.parameters.group = data.group;
			}
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

	}

	$scope.showFilterCenters = function(){
		$scope.filter_centers = [];
		for (var i = 0; i < $scope.parameters.center.length; i++) {
			if($scope.filter.cities.indexOf($scope.parameters.center[i].city_id) != -1){
				$scope.filter_centers.push($scope.parameters.center[i]);
			} else {
				var idx = $scope.filter.centers.indexOf($scope.parameters.center[i].value);
				if(idx != -1){
					$scope.filter.centers.splice(idx,1);
				}
			}
		}
	}

	$scope.resetAll = function(){
		$scope.filter_centers = [];
		$scope.filter = init_filters;
		$(".filters input[type=checkbox]").removeAttr("checked");
		$scope.hide_reset = true;
		$scope.getList();
	}

	$scope.addLead = function(){
		$scope.leadData = {
			status:1,
			action_date:$scope.current_date
		};
		$scope.checkReason(1);
		$("#add-lead").modal("show");
	}

	$scope.checkReason = function(status){
		$scope.status_row = {
			date_req: 0,
			call_note_req: 0,
			reason_req: 0,
		}
		for (var i = 0; i < $scope.parameters.status.length; i++) {
			if($scope.parameters.status[i].value == status){
				$scope.status_row = $scope.parameters.status[i];
			}
		}
		console.log($scope.status_row);
	}

	$scope.onSubmitLead = function(){
		$scope.processing_req = true;
		
		DBService.postCall( $scope.leadData,'/api/leads/store')
		.then(function(data){
			if(data.success){
				if(!$scope.leadData.id){
					$("#add-lead").modal("hide");
					$scope.dataset.unshift(data.lead);
				} else {
					$scope.edit_lead = false;
					data.lead.history = $scope.leadData.history; // so that history does not go away
					$scope.leadData = data.lead;

				}
			}
			bootbox.alert(data.message);
			$scope.processing_req = false;
		});
		
	}

	$scope.addNote = function(index){
		$scope.lead_index = index;
		$scope.leadData = JSON.parse(JSON.stringify($scope.dataset[index]));
		
		// $scope.noteData = JSON.parse(JSON.stringify($scope.dataset[index]));
		$scope.noteData.lead_id = $scope.leadData.id;
		$scope.noteData.assigned_to = $scope.leadData.assigned_to;
		$scope.noteData.status = 0;
		$scope.noteData.action_date = "";
		$scope.noteData.reason_id = "";	
		
		$scope.edit_loading = false;
		$("#addNote").modal("show");
		$scope.leadHistory();
		
	}

	$scope.leadHistory = function(){
		DBService.postCall({},'/api/leads/history/'+$scope.leadData.id)
		.then(function(data){
			if(data.success){
				$scope.leadData.history = data.history;
			} else {
				bootbox.alert(data.message);	
			}
			$scope.edit_loading = false;
		});
	}

	$scope.addNoteSubmit = function(){
		
		$scope.adding_note = true;
		DBService.postCall( $scope.noteData, '/api/leads/addNote')
		.then(function(data){
			if(data.success){
				$scope.leadHistory();
				$scope.noteData.status = 0;
				$scope.noteData.action_date = "";
				$scope.noteData.reason_id = "";	
				$scope.leadForm.$setPristine();
				$scope.leadData.status_value = data.lead.status_value;
				$scope.leadData.assigned_member = data.lead.assigned_member;
				$scope.leadData.action_date = data.lead.action_date;

				$scope.dataset[$scope.lead_index] = data.lead;
			}

			bootbox.alert(data.message);
			$scope.adding_note = false;
		});
	}

	$scope.getAge1 = function(){
		DBService.postCall({dob:$scope.leadData.dob},'/api/getAge').then(function(data){
			$scope.leadData.age = data;
		});
	}

	$scope.viewDetails1 = function(lead){
		$scope.openLead = {};
		$scope.openLead = lead;
		$scope.openLead.sms_text = "";
		$("#viewDetails").modal("show");
	}

	$scope.bulk_upload1 = function(){
		$("#bulk_upload_lead").modal("show");
	}

	$scope.uploadLeads = function (file) {
		$scope.errMsg = false;
		if (file) {

			$scope.bulk_upload_processing = true;
			var url = base_url+'/api/leads/bulk-lead';
	        Upload.upload({
	            url: url,
	            data: {
	            	media: file
	            }
	        }).then(function (resp) {
	        	console.log(resp);
	            if(resp.data.success){
	            	$scope.uploading_percentage = false;
	            	alert(resp.data.message);
	            } else {
	            	$scope.uploading_percentage=0;
	            	$scope.errMsg = resp.data.message;
	            }
	            $scope.bulk_upload_processing = false;
	        }, function (resp) {
	            console.log('Error status: ' + resp.status);
	            $scope.bulk_upload_processing = false;
	        }, function (evt) {
	            $scope.uploading_percentage = parseInt(100.0 * evt.loaded / evt.total);
	        });
		}
    }

	$scope.delete1 = function(lead,index){
		lead.processing = true;
		DBService.getCall('/api/leads/delete/'+lead.id).then(function(data){
			if(data.success){
				$scope.dataset.splice(index,1)
			}
			alert(data.message);
			lead.processing = false;
		});
	}	

	$scope.addSelectedLead = function(lead){
		if($scope.selectedLeads.indexOf(lead.id) > -1){
			var index = $scope.selectedLeads.indexOf(lead.id);
			$scope.selectedLeads.splice(index,1);
		} else {
			$scope.selectedLeads.push(lead.id);
		}
	}

	$scope.checkAll = function(){
		$scope.formData.checkAll = !$scope.formData.checkAll;
		for (var i = 0; i < $scope.dataset.length; i++) {
			if($scope.formData.checkAll){
				if($scope.selectedLeads.indexOf($scope.dataset[i].id) < 0){
					$scope.selectedLeads.push($scope.dataset[i].id);
				}
			} else {
				var index = $scope.selectedLeads.indexOf($scope.dataset[i].id);
				$scope.selectedLeads.splice(index,1);
			}
		}
	}

	$scope.selectAllFilterLeads = function(){
		$scope.selecting_all_leads = true;
		DBService.postCall($scope.filter,'/api/leads?type=only_ids')
		.then(function(data){
			if(data.success){
				$scope.selectedLeads = data.lead_ids;
				$scope.selecting_all_leads = false;
			}
		});
	}

	$scope.sendMessage = function(){
		$scope.formData = {};
		$("#messageForm").modal("show");
	}

	// $scope.postMessage1 = function(){
	// 	$scope.processing = true;

	// 	$scope.formData.selectedLeads = $scope.selectedLeads;

	// 	DBService.postCall($scope.formData,'/api/leads/postMessage').then(function(data){
	// 		if(data.success){

	// 			if(!data.demo_check){

	// 				$scope.formData = {};
	// 				$scope.selectedLeads =[];
	// 				$("#messageForm").modal("hide");
	// 			}

	// 		}
	// 		$scope.processing = false;

	// 		alert(data.message);
	// 	});
	// }

	$scope.getCities = function(){
		DBService.getCall('/api/cities/'+$scope.leadData.client_state_id).then(function(data){
			$scope.parameters.cities = data.cities;
		});
	}

});