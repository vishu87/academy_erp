app.controller("payments_type_controller", function($scope, $http, DBService) {

	$scope.sport_id = 0;

	$scope.edit = false;
	$scope.price_type ={};
	$scope.price_type_data = {};
	$scope.get_PTData = {};
	$scope.add_PPD = {
		city_ids:[],
		centers_ids:[],
	    groups_ids:[],
	};
	$scope.payTypePriceList = {};
	$scope.access_type_id = '';

	$scope.getCityCenterGroup = function(){
	  
	  DBService.postCall({
	  	sport_id : $scope.sport_id
	  },"/api/pay-type-price/get-pay-type-data")
	  .then(function(data){
	  	if (data.success) {
		  	$scope.price_type.pay_type_cat = data.pay_type_cat;
		  	$scope.price_type.pay_type = data.pay_type;
	  	}
	  })

	  DBService.postCall({ Tag:'pay_structure' },"/api/get-state-city-center-data")
	  .then(function(data){
	    $scope.price_type.city = data.city;
	    $scope.price_type.center = data.center;
	    $scope.price_type.group= data.group;
	  })

	}

	$scope.getPayPriceList = function(){

		if(!$scope.get_PTData.pay_type_id) return;
		
		$scope.processing = true;

	  	DBService.postCall({
	  		type_id : $scope.get_PTData.pay_type_id
	  	},"/api/pay-type-price/list")
	  		.then(function(data){
	  		if (data.success) {
	  			$scope.payTypePriceList = data.list;
	  			$scope.texPercentage = data.texPercentage;
	  			$scope.processing = false;
	  		}
	  	})
	}

	$scope.show_data_modal = function(id){
		$scope.add_PPD.tax = $scope.texPercentage.tax;
	    $('#'+id).modal('show');
	}

	$scope.hide_data_modal = function(id){
	      $('#'+id).modal('hide');
	}

	$scope.openAddPayPrice = function(id){
		$scope.hide_data_modal("price_access");
		$scope.show_data_modal(id);
	}

	$scope.showAddPayPriceData = function(id,modal){
		$scope.add_PPD = {centers_ids:[],groups_ids:[],city_ids:[]};
		$scope.edit = false;
		$scope.add_PPD.pay_type_cat_id = $scope.get_PTData.pay_type_cat_id;
		$scope.add_PPD.pay_type_id = $scope.get_PTData.pay_type_id;

		// if city_id == -1 that means Default
		  $scope.add_PPD.modal_id = id;
		  if (id == 1){
		    $scope.add_PPD.city = true;
		    $scope.add_PPD.city_id = -1;
		    $scope.openAddPayPrice('price_type_modal');
		  }

		  if (id == 2){
		    $scope.add_PPD.city = true;
		    $scope.add_PPD.center = false;
		    $scope.add_PPD.group = false;
		    $scope.show_data_modal(modal);
		  }

		  if (id == 3){
		    $scope.add_PPD.city = true;
		    $scope.add_PPD.center = true;
		    $scope.add_PPD.group = false;
		    $scope.add_PPD.city_id = 0;
		    $scope.show_data_modal(modal);
		  }

		  if (id == 4){
		    $scope.add_PPD.city = true;
		    $scope.add_PPD.center = true;
		    $scope.add_PPD.group = true;
		    $scope.add_PPD.city_id = 0;
		    $scope.show_data_modal(modal);
		  }      

	}

	$scope.add_city_in_access_location = function(id){
	  if ($scope.add_PPD.city_ids.includes(id)) {
	    $scope.add_PPD.city_ids.splice($scope.add_PPD.city_ids.indexOf(id),1);
	  } else {
	    $scope.add_PPD.city_ids.push(id);
	  }
	}

	$scope.add_center_in_access_location = function(id){
	  if ($scope.add_PPD.centers_ids.includes(id)) {
	    $scope.add_PPD.centers_ids.splice($scope.add_PPD.centers_ids.indexOf(id),1);
	  } else {
	    $scope.add_PPD.centers_ids.push(id);
	  }
	}

	$scope.add_group_in_access_location = function(id){
	  if ($scope.add_PPD.groups_ids.includes(id)) {
	    $scope.add_PPD.groups_ids.splice($scope.add_PPD.groups_ids.indexOf(id),1);
	  } else {
	    $scope.add_PPD.groups_ids.push(id);
	  }

	}

	$scope.countTotal = function(priceData){
		total = (parseInt(priceData.price * priceData.tax)/100) + parseInt(priceData.price);
		priceData['total_amt'] = total;
	}

	$scope.addPayPriceData = function(priceData){
		$scope.processing_req = true;
		DBService.postCall(
			priceData
			,"/api/pay-type-price/add"
		)
		.then(function(data){
			if (data.success) {
				bootbox.alert(data.message);
				$scope.getPayPriceList({pay_type_cat_id: priceData.pay_type_cat_id,
				 pay_type_id: priceData.pay_type_id});
			  	$scope.hide_data_modal('price_type_modal');
			  	$scope.add_PPD = {city_ids:[],centers_ids:[],groups_ids:[]};
			} else {
				bootbox.alert(data.message);	
			}
			$scope.processing_req = false;
		});
	}

	$scope.editPayPriceData = function(item){
		$scope.add_PPD.id = item.id;
		$scope.add_PPD.price = item.price;
		$scope.add_PPD.tax = item.tax;
		$scope.add_PPD.total_amt = item.total;
		$scope.edit = true;
		$scope.openAddPayPrice('price_type_modal');
	}

	$scope.updatePayPriceData = function(priceData){
		$scope.processing_req = true;
		DBService.postCall(
			priceData
			,"/api/pay-type-price/update"
		)
		.then(function(data){
			if (data.success) {
				$scope.getPayPriceList({
					pay_type_cat_id: data.entry.pay_type_cat_id,
		  			pay_type_id: data.entry.pay_type_id
		  		});
				$scope.hide_data_modal('price_type_modal');
				bootbox.alert(data.message);
			} else {
				bootbox.alert(data.message);
			}
			$scope.edit = false;
			$scope.processing_req = false;
		});
	}

	$scope.deletePayPriceData = function(id){
		bootbox.confirm("Are you sure?", (check)=>{
			if (check) {
				DBService.postCall({id:id},"/api/pay-type-price/delete")
				.then(function(data){
					if (data.success) {
						bootbox.alert(data.message);
						$scope.getPayPriceList({pay_type_cat_id: data.target.pay_type_cat_id,
				  		 pay_type_id: data.target.pay_type_id});
						$scope.hide_data_modal('price_type_modal');
					} else {
						bootbox.alert(data.message);
					}
				});
			}
		});
	}

});