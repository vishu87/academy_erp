app.controller('RenewalCtrl',function($scope , $http, $timeout , DBService){
	
    $scope.payment_code = payment_code;
	$scope.filter = {
        mobile_number: "9634628573"
    }
    $scope.step = 1;
	$scope.key = "";
	$scope.students = [];
    $scope.student = {};
    $scope.sub = {};
    $scope.sub_index = -1;
    $scope.show_success = false;
    $scope.datetime = "";

    $scope.formData = {};
    $scope.coupon_code = "";

	$scope.clickStep = function(number){
		$scope.step = number;
	}

	$scope.submitStep1 = function(){

        if($scope.filter.mobile_number.length != 10){
            alert("Please fill 10 digit mobile number");
            return;
        }

		$scope.processing = true;
		DBService.postCall({mobile_number:$scope.filter.mobile_number},'/api/renewal/search').then(function(data){
			$scope.students = data.students;
			$scope.processing = false;

            if($scope.students.length > 0){
                $scope.step = 2;
            } else {
                alert("No students found with this number");
            }

		});
	}

    $scope.selectStudent = function(student){
        $scope.student = student;
        $scope.payment_items = [];
        $scope.step = 3;
        $scope.getPaymentOptions();
    }

    $scope.getPaymentOptions = function(){
        DBService.postCall({
            group_id: $scope.student.group_id,
            payment_code: $scope.payment_code
        },"/api/subscriptions/get-payment-options").then(function(data){
            $scope.payment_options = data.payment_options;
            $scope.getPaymentItems();
        });
    }

    $scope.checkCoupon = function(){
        DBService.postCall({ 
            coupon_code: $scope.formData.coupon_code,
            group_id: $scope.student.group_id 
        },"/api/subscriptions/check-coupon").then(function(data){
            if(data.success){
                $scope.coupon_code = $scope.formData.coupon_code;
                $scope.getPaymentItems();
            } else {
                alert(data.message);
            }
        });
    }

    $scope.removeCoupon = function(){
        $scope.formData.coupon_code = "";
        $scope.coupon_code = "";
        $scope.coupon_code_message = "";
        $scope.getPaymentItems();
    }

    $scope.getPaymentItems = function(){
        DBService.postCall({ 
            coupon_code : $scope.coupon_code, 
            categories : $scope.payment_options, 
            group_id: $scope.student.group_id,
            payment_code: $scope.payment_code,
        },"/api/subscriptions/get-payment-items").then(function(data){
            $scope.payment_items = data.payment_items;
            $scope.total_amount = data.total_amount;
            $scope.total_discount = data.total_discount;

            $scope.coupon_code_message = data.coupon_code_message;

        });
    }

    $scope.createOrder = function(){
        $scope.placing_order = true;
        DBService.postCall({
            type: "renewal",
            payment_gateway: "razorpay",
            student_id: $scope.student.id,
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
            "name": $scope.student.name,
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
                "name": $scope.student.name,
                "email": "",
                "contact": $scope.filter.mobile_number
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
