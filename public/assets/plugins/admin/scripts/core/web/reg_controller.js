app.controller("Reg_controller", function($scope, $http, DBService) {
   
   $scope.processing = false;
  	// $scope.formData = {
  	// 	name : "Vashisths",
  	// 	date: "10",
  	// 	month : "10",
  	// 	year: "2009",
  	// 	gender: "1",
  	// 	father_name : "RK",
  	// 	mother_name : "Alka",
  	// 	prim_email : "vishu.iitd@gmail.com",
  	// 	prim_mobile : "9421345321",
  	// 	prim_relation_to_student : "father",
  	// 	address: "Asdada,asda dad , asda",
  	// 	state_id : "10",
  	// 	city_id: "3",
  	// 	pin_code : "265122",
  	// 	training_city_id : "3",
  	// 	training_center_id : "8"

  	// };

    $scope.formData = {
        name : "",
        date: "",
        month : "",
        year: "",
        gender: "",
        father_name : "",
        mother_name : "",
        prim_email : "",
        prim_mobile : "",
        prim_relation_to_student : "",
        address: "",
        state_id : "",
        city_id: "",
        pin_code : "",
        training_city_id : "",
        training_center_id : ""
    };

  	$scope.tab = 1;
  	
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

    $scope.getStateCity = function(){
      DBService.getCall("/api/cities/"+$scope.formData.address_state_id).then(function(data){
        if (data.success) {
          $scope.state_cities = data.cities;
        }else{
          bootbox.alert(data.message);  
        }
      });
    }

  	$scope.getPaymentOptions = function(){
		DBService.postCall({group_id: $scope.formData.group_id},"/api/subscriptions/get-payment-options").then(function(data){
		  	$scope.payment_options = data.payment_options;
		  	$scope.getPaymentItems();
		});
  	}
  	$scope.getPaymentOptions();

  	$scope.getPaymentItems = function(){
		DBService.postCall({ categories : $scope.payment_options, group_id: $scope.formData.group_id },"/api/subscriptions/get-payment-items").then(function(data){
		  	$scope.payment_items = data.payment_items;
		  	$scope.total_amount = data.total_amount;
		});
  	}


  	$scope.onSubmit = function(){
        $scope.processing = true;
  		$scope.formData.payment_items = $scope.payment_items;
	  	DBService.postCall($scope.formData,"/api/registrations/store").then(function(data){
		  	if (data.success) {
          $scope.reg_data = data.reg_data;
          $scope.formData.id = $scope.reg_data.id;
		  		$scope.tab = 2;
		  	} else {
		  		bootbox.alert(data.message);	
		  	}
            $scope.processing = false;
		});
  	}

  	$scope.showSizeChart = function(){
  		$("#kit_size").modal('show');
  	}

  	$scope.createOrder = function(){
        $scope.placing_order = true;
        DBService.postCall({
            type: "registration",
            payment_gateway: "razorpay",
            registration_id: $scope.formData.id,
            total_amount : $scope.total_amount,
            payment_items : $scope.payment_items
        },'/api/subscriptions/create-order').then(function(data){
            $scope.order_id = data.order_id;
            $scope.key = data.key;
            $scope.startPayment();
            $scope.placing_order = false;
        });
    }

    $scope.startPayment = function(){
        var options = {
            "key": $scope.key, 
            "amount": $scope.total_amount*100, 
            "currency": "INR",
            "name": $scope.reg_data.name,
            "description": "BBFS Registration Payment",
            "image": "https://www.bbfootballschools.com/images/logo.png",
            "order_id": $scope.order_id, 
            "handler": function (response){

                $scope.processing_order = true;
                $scope.transaction_id = response.razorpay_payment_id;

                DBService.postCall({
                    order_id: $scope.order_id,
                    transaction_id: response.razorpay_payment_id
                },'/api/subscriptions/process-order').then(function(data){
                    $scope.datetime = data.datetime;
                    if(data.success){
                        $scope.show_success = true;
                    } else {
                        alert(data.message);
                    }
                    $scope.processing_order = false;
                });

            },
            "prefill": {
                "name": $scope.reg_data.name,
                "email": $scope.reg_data.prim_email,
                "contact": $scope.reg_data.prim_mobile
            },
            "notes": {
                "address": "BBFS"
            },
            "theme": {
                "color": "#F37254"
            },
            "modal": {
                "ondismiss": function(){
                  	alert("Payment has been cancelled. Kindly retry");
                }
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    }

});