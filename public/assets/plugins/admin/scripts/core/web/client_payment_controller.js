app.controller('ClientPaymentCtrl',function($scope , $http, $timeout , DBService){
	
    $scope.payment_code = payment_code;
    $scope.invalid = false;
    $scope.show_success = false;

	$scope.init = function(){
        $scope.loading = true;
        DBService.postCall({
            payment_code : $scope.payment_code
        },'/api/subscriptions/get-payment').then(function(data){
            if(data.success){
                $scope.student = data.student;
                $scope.payment = data.payment;
                $scope.total_amount = data.total_amount;
                $scope.total_discount = data.total_discount;
            } else {
                $scope.invalid = true;
            }
            $scope.loading = false;
        });
    }

    $scope.init();

    $scope.createOrder = function(){
        $scope.placing_order = true;
        DBService.postCall({
            type: "renewal",
            payment_gateway: "razorpay",
            student_id: $scope.payment.student_id,
            payment_id: $scope.payment.id,
            total_amount : $scope.total_amount,
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
                "contact": $scope.student.mobile_number
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
