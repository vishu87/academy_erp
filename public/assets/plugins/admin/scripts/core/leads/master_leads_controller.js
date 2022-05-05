app.controller('master_leads_controller', function($scope,$http,DBService,Upload){

	$scope.leads_for = [];
	$scope.lead_status = [];
	$scope.lead_reasons = [];
	$scope.lead_sources = [];

	$scope.lead_for = {};
	$scope.lead_sts = {};
	$scope.lead_reason = {};
	$scope.lead_source = {};


	$scope.init = function(){
		$scope.leadsFor();
		$scope.leadStatus();
		$scope.leadReasons();
		$scope.leadSources();
	}

	$scope.leadsFor = function(){
		DBService.getCall('/api/master-leads/leads-for').then(function(data){
			if(data.success){
				$scope.leads_for = data.leadsFor;
			}
		});
	}

	$scope.leadStatus = function(){
		DBService.getCall('/api/master-leads/lead-status').then(function(data){
			if(data.success){
				$scope.lead_status = data.leadStatus;
			}
		});
	}

	$scope.leadReasons = function(){
		DBService.getCall('/api/master-leads/lead-reasons').then(function(data){
			if(data.success){
				$scope.lead_reasons = data.leadReasons;
			}
		});
	}

	$scope.leadSources = function(){
		DBService.getCall('/api/master-leads/lead-sources').then(function(data){
			if(data.success){
				$scope.lead_sources = data.leadSources;
			}
		});
	}

	$scope.createLeadFor = function(){
		$scope.lead_for = {};
		$("#lead_for_modal").modal('show');
	}

	$scope.submitLeadFor = function(){
		$scope.leadForProcessing = true;
		DBService.postCall($scope.lead_for,'/api/master-leads/lead-for-store').then(function(data){
			if(data.success){
				bootbox.alert(data.message);
				$("#lead_for_modal").modal('hide');
				$scope.leadsFor();

			} else {
				bootbox.alert(data.message);
			}
			$scope.leadForProcessing = false;
		});
	}

	$scope.editLeadFor = function($index){
		$scope.lead_for = JSON.parse(JSON.stringify($scope.leads_for[$index]));
		$("#lead_for_modal").modal('show');
	}

	$scope.deleteLeadFor = function(lead_for_id, $index){
		bootbox.confirm("Are you sure?", (check)=>{
      	if (check) {
			DBService.getCall('/api/master-leads/lead-for-delete/'+lead_for_id).then(function(data){
				if(data.success){
					bootbox.alert(data.message);
					$scope.leads_for.splice($index,1);
				}
			});
		}
		});
	}

	$scope.editLeadStatus = function($index){
		$scope.lead_sts = JSON.parse(JSON.stringify($scope.lead_status[$index]));
		$("#lead_status_modal").modal('show');
	}

	$scope.submitLeadStatus = function(){
		$scope.leadstatusProcessing = true;
		DBService.postCall($scope.lead_sts,'/api/master-leads/lead-status').then(function(data){
			if(data.success){
				bootbox.alert(data.message);
				$("#lead_status_modal").modal('hide');
				$scope.leadStatus();

			} else {
				bootbox.alert(data.message);
			}
			$scope.leadstatusProcessing = false;
		});
	}

	$scope.createLeadReason = function(){
		$scope.lead_reason = {};
		$("#lead_reason_modal").modal('show');
	}

	$scope.submitLeadReason = function(){
		$scope.leadReasonProcessing = true;
		DBService.postCall($scope.lead_reason,'/api/master-leads/lead-reason').then(function(data){
			if(data.success){
				bootbox.alert(data.message);
				$("#lead_reason_modal").modal('hide');
				$scope.leadReasons();

			} else {
				bootbox.alert(data.message);
			}
			$scope.leadReasonProcessing = false;
		});
	}

	$scope.editLeadReasons = function($index){
		$scope.lead_reason = JSON.parse(JSON.stringify($scope.lead_reasons[$index]));
		$("#lead_reason_modal").modal('show');
	}

	$scope.deleteLeadReasons = function(lead_reason_id, $index){
		bootbox.confirm("Are you sure?", (check)=>{
      	if (check) {
			DBService.getCall('/api/master-leads/lead-reason-delete/'+lead_reason_id).then(function(data){
				if(data.success){
					bootbox.alert(data.message);
					$scope.lead_reasons.splice($index,1);
				}
			});
		}
		});
	}

	$scope.createLeadSources = function(){
		$scope.lead_source = {};
		$("#lead_source_modal").modal('show');
	}

	$scope.submitLeadSource = function(){
		$scope.leadSourceProcessing = true;
		DBService.postCall($scope.lead_source,'/api/master-leads/lead-source').then(function(data){
			if(data.success){
				bootbox.alert(data.message);
				$("#lead_source_modal").modal('hide');
				$scope.leadSources();

			} else {
				bootbox.alert(data.message);
			}
			$scope.leadSourceProcessing = false;
		});
	}

	$scope.editLeadSources = function($index){
		$scope.lead_source = JSON.parse(JSON.stringify($scope.lead_sources[$index]));
		$("#lead_source_modal").modal('show');
	}

	$scope.deleteLeadSources = function(lead_source_id, $index){
		bootbox.confirm("Are you sure?", (check)=>{
      	if (check) {
			DBService.getCall('/api/master-leads/lead-source-delete/'+lead_source_id).then(function(data){
				if(data.success){
					bootbox.alert(data.message);
					$scope.lead_sources.splice($index,1);
				}
			});
		}
	});
	}	


});