app.controller('RenewalCtrl',function($scope , $http, $timeout , DBService){
	
	$scope.filter = {
        mobile_number: ""
    }
    $scope.step = 1;
	$scope.key = "";
	$scope.students = [];
    $scope.student = {};
    $scope.sub = {};
    $scope.sub_index = -1;
    $scope.show_success = false;
    $scope.datetime = "";

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

            // $scope.student = $scope.students[0];
            // $scope.getPackage();
            if($scope.students.length > 0){
                $scope.step = 2;
            } else {
                alert("No students found with this number");
            }

		});
	}
    // $scope.submitStep1();

    $scope.selectStudent = function(student){
        $scope.student = student;
        $scope.step = 3;
        $scope.getPackage();
    }

    $scope.getPackage = function(){
        $scope.processing = true;
        DBService.postCall({student_id:$scope.student.id},'/api/renewal/find-subs').then(function(data){
            $scope.subs = data.subs;
            $scope.message = data.message;
            $scope.processing = false;
            if(data.subs.length > 0){
                $scope.selectSub($scope.subs[$scope.subs.length - 1],$scope.subs.length - 1);
            }
        });
    }

    $scope.selectSub = function(sub, index){
        $scope.sub_index = index;
        $scope.sub = sub;
    }

    $scope.createOrder = function(){
        $scope.placing_order = true;
        DBService.postCall({
            student: $scope.student,
            subscription: $scope.sub
        },'/api/renewal/create-order').then(function(data){
            $scope.order_id = data.order_id;
            $scope.key = data.key;
            $scope.startPayment();
            $scope.placing_order = false;
        });
    }

    $scope.startPayment = function(){
        var options = {
            "key": $scope.key, 
            "amount": $scope.sub.total_amount*100, 
            "currency": "INR",
            "name": $scope.student.name,
            "description": "BBFS Renewal Payment for #"+$scope.student.code,
            "image": "https://www.bbfootballschools.com/images/logo.png",
            "order_id": $scope.order_id, 
            "handler": function (response){

                $scope.processing_order = true;
                $scope.transaction_id = response.razorpay_payment_id;

                DBService.postCall({
                    order_id: $scope.order_id,
                    transaction_id: response.razorpay_payment_id
                },'/api/renewal/process-order').then(function(data){
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
                "email": $scope.student.email,
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
                    alert("Payment has been cancelled");
                }
            }
        };
        var rzp1 = new Razorpay(options);

        rzp1.open();
    }

});
