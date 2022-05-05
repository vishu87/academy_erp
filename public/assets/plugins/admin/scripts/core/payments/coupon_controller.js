app.controller("coupon_controller", function($scope, $http, DBService) {

	$scope.edit = false;
	$scope.price_type ={};
	$scope.price_type_data = {};
	$scope.couponData = {};
	$scope.formData = {};
	$scope.payTypePriceList = {};
	$scope.access_type_id = '';
	$scope.add_PPD = {
		city_ids:[],
		centers_ids:[],
	    groups_ids:[],
	};
	
	$scope.access_location_data = {
      centers_ids:[],
      groups_ids:[]
    };
  
  $scope.access_right_type =
    [
      {'id':1, 'type':"All City Access"},
      {'id':2, 'type':"City"},
      {'id':3, 'type':"Center"},
      {'id':4, 'type':"Group"}
  ];

	$scope.init = function(){
	  
	  $scope.processing = true;
	  
	  DBService.postCall({
	  	sport_id : $scope.sport_id
	  },"/api/pay-type-price/get-pay-type-data")
	  .then(function(data){
	  	if (data.success) {
		  	$scope.price_type.pay_type_cat = data.pay_type_cat;
		  	$scope.price_type.pay_type = data.pay_type;
	  	}
	  })

	  DBService.postCall({Tag:'pt-edit'},"/api/get-state-city-center-data")
	  .then(function(data){
	    $scope.price_type.city = data.city;
	    $scope.price_type.center = data.center;
	    $scope.price_type.group= data.group;
	    $scope.processing = false;
	  })
	}

	$scope.getCouponList = function(){
		DBService.postCall({
			sport_id: $scope.sport_id
		},"/api/coupons/get-coupons-list")
		  .then(function(data){
		    $scope.couponData = data.coupons;
		    $scope.processing = false;
	  })
	}

	$scope.showCouponModal = function(){
		$scope.formData = {};
		$("#coupon_type_modal").modal('show');
	}

	$scope.getLocations = function(tag){

      DBService.postCall({Tag:tag},"/api/get-state-city-center-data")
      .then(function(data){
        $scope.all_city_list = data.city;
        $scope.all_center_list = data.center;
        $scope.all_group_list = data.group;
        $scope.ac_provideer = data.all_access;
      });
    }

	$scope.addCoupon = function(add_PPD){
		$scope.couponProcessing = true;
		$scope.formData.sport_id = $scope.sport_id;
		DBService.postCall($scope.formData,"/api/coupons/add")
		.then(function(data){
		  	if (data.success) {
		  		bootbox.alert(data.message);
		  		$scope.getCouponList();
		  		$("#coupon_type_modal").modal('hide');
		  	} else {
		  		bootbox.alert(data.message);	
		  	}
		  	$scope.couponProcessing = false;
		});
	}

	$scope.editCoupon = function(coupon){
		$scope.formData = JSON.parse(JSON.stringify(coupon));
		$("#coupon_type_modal").modal('show');
	}

	$scope.deleteCoupon = function(data, index){

		bootbox.confirm("Are you sure?", (check) => {
			if(check){
				DBService.getCall("/api/coupons/delete-coupon/"+data.id)
				  .then(function(data){
				  	if (data.success) {
				  		bootbox.alert(data.message);
				  		$scope.couponData.splice(index,1);
				  	}else{
				  		bootbox.alert(data.message);	
				  	}
				});
			}
		});
	}

	$scope.show_location_access_model = function(item){

	  $scope.coupon_id = item.id;
      var id = item.type;
      $scope.getLocations();
      
      if (id == 1){
        $scope.access_location_data.modal_id = 1;
        $scope.access_location_data.city = true;
        $scope.access_location_data.city_id = -1;
        $scope.submit_access_location_data();

      }

      if (id == 2){
        $scope.access_location_data.modal_id = 2;
        $scope.access_location_data.city = true;
        $scope.access_location_data.center = false;
        $scope.access_location_data.group = false;
        $scope.access_location_data.city_id = 0;
        $("#location_access").modal('show');
      }

      if (id == 3){
        $scope.access_location_data.modal_id = 3;
        $scope.access_location_data.city = true;
        $scope.access_location_data.center = true;
        $scope.access_location_data.group = false;
        $scope.access_location_data.city_id = 0;
        $("#location_access").modal('show');
      }

      if (id == 4){
        $scope.access_location_data.modal_id = 4;
        $scope.access_location_data.center = true;
        $scope.access_location_data.city = true;
        $scope.access_location_data.group = true;
        $scope.access_location_data.city_id = 0;
        $("#location_access").modal('show');
      }      

    }

    $scope.add_center_in_access_location = function(id){
      if ($scope.access_location_data.centers_ids.includes(id)) {
        $scope.access_location_data.centers_ids.splice($scope.access_location_data.centers_ids.indexOf(id),1);
      } else {
        $scope.access_location_data.centers_ids.push(id);
      }
    }

    $scope.add_group_in_access_location = function(id){
      if ($scope.access_location_data.groups_ids.includes(id)) {
        $scope.access_location_data.groups_ids.splice($scope.access_location_data.groups_ids.indexOf(id),1);
      } else {
        $scope.access_location_data.groups_ids.push(id);
      }
    }

    $scope.submit_access_location_data = function(){
      	DBService.postCall({
      		availibility:$scope.access_location_data,
      		coupon_id:$scope.coupon_id
      	},
        "/api/coupons/add-availibility")
      	.then(function(data){
        	if (data.success == true){
        		alert(data.message);
          		$scope.getCouponList();
        	} else {
          		alert(data.message);
        	}
      	});
      
      	$scope.hide_location_access_model();
    }

    $scope.hide_location_access_model = function(){
      $("#location_access").modal('hide');
    }

    $scope.delete_coupon_mapping = function(loc){
    	
    	bootbox.confirm("Are you sure?", (check) => {
    		if(check){
    			DBService.getCall("/api/coupons/delete-availibility/"+loc.id)
				  .then(function(data){
				  	if (data.success) {
				  		bootbox.alert(data.message);
				  		$scope.getCouponList();
				  	}else{
				  		bootbox.alert(data.message);	
				  	}
					});
    		}
    	});
    }

});