app.controller("Students_profile_controller", function($scope, $http, DBService, Upload) {
    
    $scope.sport_id = 0;
    $scope.student = {};
    $scope.state_city_center = {};
    $scope.loading = true;
    $scope.processing = false;
    $scope.switchContent = 'payments';
    $scope.performance_category_id = 0;

    $scope.attendance = {
      weeks: []
    }

    $scope.p_categories = [];

    $scope.month = "";
    $scope.year = "";
    
    $scope.myImage = '';
    $scope.myCroppedImage = '';
    $scope.docType = [];
    $scope.inactiveReasons = [];
    $scope.payType = [];
    $scope.payTypeCat = [];
    $scope.payModes = [];
    $scope.item = {
      "category_id":'',
      "type_id":'',
      "amount":'',
      "tax":'',
      "total":'',
    };
    $scope.guardians = {name:'',mobile:'',email:'',type_id:''};
    $scope.add_student = { guardians:[] };

    $scope.student_details = function(id){
      $scope.student_id = id;
      $scope.getStateCityCenter('st-edit');

      DBService.postCall({ student_id : id },
       "/api/student/details")
      .then(function (data){  
          $scope.student = data.student;
          if(!data.student.payment_access){
            $scope.switchContent = 'subscriptions'; 
          }
          $scope.loading = false;
      });

      $scope.getAttendance();
      $scope.getPerformanceReports();
      $scope.getPerformanceGraph();
    }

    $scope.editStudent = function(id){
      $scope.getStateCityCenter('st-edit');
      if (id > 0) {
        DBService.postCall({student_id:id},"/api/student/edit-student") 
        .then(function(data){
            if (data.success) {
              $scope.add_student = data.student;
              $scope.add_student.edit =  true;
              $scope.selectCity(data.student.state_id);
              $scope.loading = false;
            } else {
              bootbox.alert(data.message);
            }
        });
      } else {
        $scope.loading = false;
      }
    }

    $scope.selectCity = function(state_id){
      DBService.getCall("/api/cities/"+state_id)
      .then(function(data){
        if (data.success) {
          $scope.cities = data.cities;
        }
      });
    }

    $scope.getStateCityCenter = function(tag){
      DBService.postCall({Tag:tag},
       "/api/get-state-city-center-data")
      .then(function (data){  
        if (data.success) {
          $scope.state_city_center = data;
        }
      });
    }

    $scope.addGuardian = function(){
      $scope.add_student.guardians.push(JSON.parse(JSON.stringify($scope.guardians)));
    }

    $scope.removeGuardian = function(index){
      $scope.add_student.guardians.splice(index,1);
    }

    // $scope.update_student_details = function (id){
    //   $scope.getStateCityCenter('st-edit'); 

    //   DBService.postCall({student_id : id},
    //    "/api/student/get-student-details-for-update")
    //   .then(function (data){     
    //     if (data.success) {
    //       $scope.add_student = data.student;
    //     }   
    //   });   
    // }
    
    // $scope.Delete = function(id, index){
    //   bootbox.confirm("Are you sure?", (check)=>{
    //     if(check){
    //       DBService.postCall({},"/students/delete-user/"+id)
    //       .then(function (data){
    //         if(data.success == true){
    //           $scope.student_list.splice(index,1);        
    //         }   
    //       });
    //     }
    //   });
    // }

    $scope.add_data = function (){
      $scope.processing = true;
      DBService.postCall(
        $scope.add_student,
       "/api/student/add-student"
      )
      .then(function (data){   
        if(data.success){
          $scope.message = data.message;
          if(!$scope.add_student.id){
            $("#addStudentModal").modal('show');
            $scope.location = base_url+"/students/student_details/"+data.studentId;
          } else {
            location.href = base_url+"/students/student_details/"+data.studentId;
          }
        } else {
          bootbox.alert(data.status.message);
        }
        $scope.processing = false;
      });
    }

    $scope.reloadRoute = function() {
      location.reload();
    }

    $scope.changeProfilePicture = function(picture){
      DBService.postCall({
        stduent_id : $scope.student.id,
        picture : picture
      },'/api/student/change-profile-pic')
      .then(function(data){
          if (data.success) {
            bootbox.alert(data.message);
          }else{
            bootbox.alert(data.message);
          }
      });
    }

    $scope.saveDocuments = function(){
      $scope.processing_req = true;
      $scope.document.student_id = $scope.student_id;
      DBService.postCall(
        $scope.document,
        "/api/student/save-documents")
      .then(function(data){
          if (data.success) {
            bootbox.alert(data.message);
            $scope.student_details($scope.student_id);
            $('#documents_modal').modal('hide');
            $scope.document = {};
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing_req = false;
      });
    }

    $scope.uploadDocument = function (file) {
      var url = base_url+'/uploads/file';
          Upload.upload({
              url: url,
              data: {
                file: file
              }
          }).then(function (resp) {
              if(resp.data.success){
                $scope.document.document_url = resp.data.path;
                $scope.document.prev_url = resp.data.url;
              } else {
                bootbox.alert(resp.data.message);
              }
              $scope.uploading_file = false;
          }, function (resp) {
              $scope.uploading_file = false;
          }, function (evt) {
              $scope.uploading_percentage = parseInt(100.0 * evt.loaded / evt.total) + '%';
          });
    }

    $scope.addInFilterArray = function(type, value){
      var index = $scope.filter[type].indexOf(value);
      if( index < 0){
        $scope.filter[type].push(value);
      } else {
        $scope.filter[type].splice(index,1);
      }
    }

    $scope.applyFilters = function(){
      $scope.page_no = 1;
      $scope.fetchStudentData();
    }

    $scope.hide_data_modal = function(id){
      $scope.editModal  = false;
      $('#'+id).modal('hide');
    }

    $scope.groupShift = function(){
      shift_data = $scope.groupShifting;
      shift_data.student_id = $scope.student_id;
      $scope.processing_req = true;
      DBService.postCall(
        shift_data
        ,"/api/student/group-change"
      )
      .then(function(data){
        if (data.success) {
            bootbox.alert(data.message);
            $scope.student_details($scope.student.id);
            $scope.hide_data_modal('group_shift_modal');
        } else {
          bootbox.alert(data.message);
        }
        $scope.processing_req = false;
      })
    }

    $scope.switchContentFun = function(tag){
      $scope.switchContent = tag;
    }

    var handleFileSelect=function(evt) {
      var file=evt.currentTarget.files[0];
      var reader = new FileReader();
      reader.onload = function (evt) {
        $scope.$apply(function($scope){
          $scope.myImage = evt.target.result;
        });
      };
      reader.readAsDataURL(file);
    };

    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);

    $scope.discard = function () {
      $scope.myImage='';
      $scope.myCroppedImage='';
      $("#playerPhoto").modal("hide");
    }

    $scope.selectCroppedChangePic = function () {
        $scope.uploading = true;
        name = 'photo';
        var url = base_url+'/upload/photo';
        Upload.upload({
            url: url,
            data: {
              photo: Upload.dataUrltoBlob($scope.myCroppedImage, name),
              resize: 1,
              crop: 1,
              width: 400,
              height: 400
            },
        }).then(function (response) {
            if(response.data.success){
                $scope.myImage='';
                $scope.myCroppedImage='';

                $scope.changeProfilePicture(response.data.path);
                $scope.student.pic = response.data.url;
                $("#ChangeplayerPhoto").modal("hide");

            } else {
                bootbox.alert(data.message);
            }
            $scope.uploading = false;

        }, function (response) {
            if (response.status > 0) $scope.errorMsg = response.status 
                + ': ' + response.data;
            $scope.uploading = false;
        }, function (evt) {
            $scope.progress = parseInt(100.0 * evt.loaded / evt.total);
        });
    }

    $scope.addInactive = function(){
      $scope.inactive = {};
      $scope.getInactiveReason();
      $('#inactive_modal').modal('show');
    }

    $scope.editInactive = function(inactive){
      $scope.editModal  = true;
      $scope.inactive = inactive;
      $scope.getInactiveReason();
      $('#inactive_modal').modal('show');
    }

    $scope.saveInactive = function(inactive){
      $scope.processing_req = true;
      inactive.student_id = $scope.student.id;
      DBService.postCall(inactive,"/api/student/save-inactive")
      .then(function(data){
          if (data.success) {
            bootbox.alert(data.message);
            $scope.student_details($scope.student.id);
            $('#inactive_modal').modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing_req = false;
      });
    }

    $scope.deleteInactive = function(id){
      bootbox.confirm("Are you sure?", (check)=>{
        if (check) {
          DBService.postCall({ id : id},"/api/student/delete-inactive")
          .then(function(data){
              if (data.success) {
                bootbox.alert(data.message);
                $scope.student_details($scope.student.id);
              }else{
                bootbox.alert(data.message);
              }
          });
        }
      })
    }

    $scope.addInjury = function(){
      $scope.editModal  = false;
      $scope.injury = {};
      $('#injury_modal').modal('show');
    }

    $scope.editInjury = function(injury){
      $scope.editModal  = true;
      $scope.injury = injury;
      $('#injury_modal').modal('show');
    }

    $scope.saveInjury = function(injury){
      injury.student_id = $scope.student.id;
      $scope.processing_req = true;
      DBService.postCall(
        injury
      ,"/api/student/save-injury")
      .then(function(data){
          if (data.success) {
            bootbox.alert(data.message);
            $scope.student_details($scope.student.id);
            $('#injury_modal').modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing_req = false;
      });
    }

    $scope.deleteInjury = function(id){
      bootbox.confirm("Are you sure?", (check)=>{
        if (check) {
          DBService.postCall({id:id},"/api/student/delete-injury")
          .then(function(data){
              if (data.success) {
                bootbox.alert(data.message);
                $scope.student_details($scope.student.id);
              }else{
                bootbox.alert(data.message);
              }
          });
        }
      })
    }

    $scope.addGroupShift = function(){
        $scope.editModal  = false;
        $scope.groupShifting = {};
        $('#group_shift_modal').modal('show');
    }

    $scope.deleteGroupShift = function(id){
      bootbox.confirm("Are you sure?", (check)=>{
        if (check) {
          DBService.postCall({id:id},"/api/student/delete-group-shift")
          .then(function(data){
              if (data.success) {
                bootbox.alert(data.message);
                $scope.student_details($scope.student.id);
              }else{
                bootbox.alert(data.message);
              }
          });
        }
      })
    }

    $scope.addDocument = function(){
        $scope.document = {};
        $scope.editModal  = false;
        $scope.getDocumentsType();
        $('#documents_modal').modal('show');
    }

    $scope.getInactiveReason = function(){
      DBService.postCall({},"/api/student/get-inactive-reason")
      .then(function(data){
          if (data.success) {
            $scope.inactiveReasons = data.inactiveReasons;
          }
      });
    }

    $scope.getDocumentsType = function(){
      DBService.postCall({ },"/api/student/get-document-type")
      .then(function(data){
          if (data.success) {
            $scope.docType = data.docType;
          }
      });
    }


    $scope.getPaymentType = function(){
      DBService.getCall("/api/student/payment/get-type/"+$scope.sport_id)
      .then(function(data){
        if (data.success) {
          $scope.payType = data.payType;
          $scope.payTypeCat = data.payTypeCat;
          $scope.payModes = data.payModes;
        }
      });
    }

    $scope.addPayment = function(type){
        $scope.getPaymentType();
        DBService.postCall({
          student_id : $scope.student.id,
          type: type,
          payment_id : 0
        },
        "/api/student/payment/init")
        .then(function(data){
          if (data.success) {
            $scope.payment = data.payment;
            $("#paymentModal").modal("show");
            $scope.getCoupon($scope.student_id);
          } else {
            bootbox.alert(data.message);
          }
        });      
    }

    $scope.addPaymentItem = function(){
      $scope.payment.items.push(JSON.parse(JSON.stringify($scope.item)));
      $scope.calculateTotal();
    }

    $scope.removePaymentItem = function(index){
      $scope.payment.items.splice(index,1);
      $scope.calculateTotal();
    }

    $scope.getAmount = function(){
      
      $scope.getting_amount = true;

      if(!$scope.item.type_id) return;
      
      DBService.postCall({
        group_id: $scope.student.group_id,
        category_id : $scope.item.category_id,
        type_id : $scope.item.type_id,
      }, "/api/student/payment/get-amount" )
      .then(function(data){
          $scope.item.category = data.category_name;
          $scope.item.type = data.type_name;
          $scope.item.months = data.months;
          $scope.item.amount = data.price.price;
          $scope.item.discount = 0;
          $scope.item.taxable_amount = $scope.item.amount - $scope.item.discount;
          $scope.item.tax_perc = data.price.tax_perc;
          $scope.item.tax = Math.round(data.price.price*data.price.tax_perc/100);
          $scope.item.total_amount = $scope.item.amount + $scope.item.tax;

          $scope.getting_amount = false;
      });

    }

    $scope.applyCoupon = function(){
      DBService.postCall({
        coupon_id : $scope.item.coupon_id,
        items : $scope.payment.items,
      }, "/api/student/payment/apply-coupon" )
      .then(function(data){
          $scope.payment.items = data.items;
          $scope.calculateTotal();
      });
    }

    $scope.applyTax = function(item){
      if (item.amount) {
        
        item.taxable_amount = parseFloat(item.amount) - parseFloat(item.discount);

        item.tax = Math.round( item.taxable_amount * parseFloat(item.tax_perc) /100 );
        
        item.total_amount = item.taxable_amount + item.tax;
        
        $scope.calculateTotal();
      }
    }

    $scope.calculateTotal = function(){
      var amount = 0;
      var tax = 0;
      for (var i = 0; i < $scope.payment.items.length; i++) {
        if ($scope.payment.items[i].taxable_amount) amount += parseFloat($scope.payment.items[i].taxable_amount);
        if ($scope.payment.items[i].tax) tax += parseFloat($scope.payment.items[i].tax);
      }
      $scope.payment.amount = amount;
      $scope.payment.tax = tax;
      $scope.payment.total_amount = amount + tax;
    }

    $scope.savePayment = function(){
      $scope.processing = true;
      $scope.payment.student_id  = $scope.student.id;
      DBService.postCall(
        $scope.payment
        ,"/api/student/payment/save-payment")
      .then(function(data){
          if (data.success) {
            bootbox.alert(data.message);
            $scope.student_details($scope.student.id);
            $scope.processing = false;
            $("#paymentModal").modal("hide");
          }else{
            bootbox.alert(data.message);
            $scope.processing = false;
          }
      });
    }

    $scope.editPayment = function(payment_id){
      $scope.getPaymentType();
      DBService.postCall({
        payment_id:payment_id
      },"/api/student/payment/edit-payment")
      .then(function(data){
          if (data.success) {
              $scope.payment = data.payment;
              $scope.payment.edit = true;
              $("#paymentModal").modal("show");
              $scope.getCoupon($scope.student_id);
          } else {
            bootbox.alert(data.message);
          }
      });
    }

    $scope.getCoupon = function(student_id){
      DBService.postCall({
        student_id:student_id
      },"/api/student/payment/get-coupons")
      .then(function(data){
          if (data.success) {
              $scope.coupons = data.coupons;
          }
      });
    }

    $scope.deleteDocuement = function(id,index){
      bootbox.confirm("Are you sure?", function(check){
        if (check) {
          DBService.postCall({id:id},"/api/student/delete-document")
          .then(function(data){
              if (data.success) {
                bootbox.alert(data.message);
                $scope.student.documents.splice(index,1);
              }else{
                bootbox.alert(data.message);
              }
          })
        }
      });
    }

    $scope.viewSubscription = function(item_id){
        $scope.open_subscription = {};
        DBService.getCall("/api/student/subscription/view/"+item_id)
        .then(function(data){
          if (data.success) {
            $scope.open_subscription = data.subscription;
            $("#subModal").modal("show");
          } else {
            bootbox.alert(data.message);
          }
      });
    }

    $scope.editSubscription = function(item){
      $scope.open_subscription = {};
      DBService.getCall("/api/student/subscription/view/"+item.id)
        .then(function(data){
          if (data.success) {
            $scope.open_subscription = data.subscription;
            $("#pauseAddModal").modal("show");
          } else {
            bootbox.alert(data.message);
          }
      });

    }

    $scope.addPause = function(){
      $scope.adding_pause = true;
      $scope.pauseData.subscription_id  = $scope.open_subscription.id;
      $scope.pauseData.student_id  = $scope.student.id;
      DBService.postCall(
        $scope.pauseData,
        "/api/student/subscription/save"
      )
      .then(function(data){
          if (data.success) {
            $scope.student_details($scope.student.id);
            $("#pauseAddModal").modal("hide");
          }
          
          bootbox.alert(data.message);
          $scope.adding_pause = false;
      });
    }

    $scope.approvePause = function(item, type){
      $scope.open_pause = item;
      $scope.open_pause.status = type;
      $("#pauseApprovalModal").modal("show");
    }

    $scope.processPauseRequest = function(){
      $scope.processing_req = true;
      DBService.postCall(
        $scope.open_pause,
        "/api/student/subscription/approve"
      )
      .then(function(data){
          if (data.success) {
            $scope.student_details($scope.student.id);
            $("#pauseApprovalModal").modal("hide");
          }
          
          bootbox.alert(data.message);
          $scope.processing_req = false;
      });
    }

    $scope.deletePauseRequest = function(pause_id){

      bootbox.confirm("Are you sure?", (check)=>{
        if (check) {
          DBService.postCall(
            {
              pause_id : pause_id
            },
            "/api/student/subscription/delete"
          )
          .then(function(data){
              if (data.success) {
                $scope.student_details($scope.student.id);
                $("#subModal").modal("hide");
              }
              bootbox.alert(data.message);
          });
        }
      })
    }

    $scope.sendWelcomeEmail = function(){
      bootbox.confirm("Are you sure to send welcome email for "+$scope.student.name+"?", (check)=>{
        if(check){
          $scope.processing_email = true;
          DBService.postCall({
            student_id : $scope.student_id
          },"/api/student/send-welcome-email")
          .then(function (data){
            bootbox.alert(data.message);
            $scope.processing_email = false;
          });
        }
      });
    }

    $scope.getAttendance = function(){

      DBService.postCall({
        month : $scope.month,
        year : $scope.year,
      },"/api/student/attendance/"+$scope.student_id)
      .then(function (data){
          $scope.attendance.month_name = data.month_name;
          $scope.attendance.year = data.year;
          $scope.attendance.weeks = data.weeks;

          $scope.month = data.month;
          $scope.year = data.year;
          
      });
    }
    $scope.prev_month = function(){
        $scope.month--;
        if($scope.month == 0) {
            $scope.month = 12;
            $scope.year--;
        }
        $scope.getAttendance();
    }

    $scope.next_month = function(){
        $scope.month++;
        if($scope.month == 13) {
            $scope.month = 1;
            $scope.year++;
        }
        $scope.getAttendance();
    }

    $scope.getPerformanceReports = function(){
      DBService.postCall({
        
      },"/api/student/reports/"+$scope.student_id)
      .then(function (data){
          $scope.reports = data.reports;
      });
    }

    $scope.getPerformanceGraph = function(){
      $scope.p_data = {
        legends : [],
        labels : [],
        values: []
      };
      $scope.loading_graph = true;
      DBService.postCall({
        category_id: $scope.performance_category_id
      },"/api/student/performance-graph/"+$scope.student_id)
      .then(function (data){
          $scope.p_data = data.p_data;
          $scope.p_categories = data.categories;
          $scope.loading_graph = false;

      });

    }
});
