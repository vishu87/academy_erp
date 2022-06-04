app.controller("Stu_Performance_Controller", function($scope, $http, DBService, Upload) {
  $scope.cityCenter = {};
  $scope.filterData ={
    show: true,
    group_id: 13,
    session_id: 1
  };
  $scope.performanceParm = {}; 
  $scope.students = [];
  $scope.studentRecord = {};
  $scope.sessionList = [];
  $scope.open_category_id = 0;
  $scope.processing = false;
  $scope.loading = true;

  $scope.getCityCenter = function(tag){
    DBService.postCall({Tag:tag},'/api/get-state-city-center-data')
    .then(function(data){
      $scope.cityCenter = data;
      $scope.loading = false;
    });
  }

  // $scope.getCityCenter = function(tag){
  //   DBService.postCall({Tag:tag},'/api/get-state-city-center-data')
  //   .then(function(data){
  //     $scope.cityCenter = data;
  //   });
  // }

  $scope.getStudents = function(){
    $scope.processing_req = true;
    DBService.postCall({group_id: $scope.filterData.group_id, session_id: $scope.filterData.session_id},'/api/student/performance/students').then(function(data){
        $scope.students = data.students;
        if(data.students.length > 0){
          $scope.studentRecord.student_id = data.students[0].id;
          $scope.studentRecord.student_name = data.students[0].name;
          $scope.processing_req = false;
          $scope.getStudentRecord();
        }
        $scope.processing_req = false;
    });
  } 

  $scope.switchStudent = function(student){
    $scope.studentRecord.student_id = student.id;
    $scope.studentRecord.student_name = student.name;
    $scope.getStudentRecord();
  }

  $scope.getStudentRecord = function(){
    DBService.postCall({
      student_id: $scope.studentRecord.student_id,
      session_id: $scope.filterData.session_id,
    },
    '/api/student/performance/student-record')
    .then(function(data){
        $scope.studentRecord.skill_categories = data.skill_categories;
        $scope.open_category_id = data.skill_categories[0].id;
    });
  }

  $scope.openHeader = function(category){
    $scope.open_category_id = category.id;
  }

  $scope.ratingMarked = function(attribute, value){
    attribute.value = value;
  }

  $scope.submitRecord = function(type){
    $scope.processing = true;
    DBService.postCall({
      studentRecord: $scope.studentRecord,
      session_id: $scope.filterData.session_id,
      type: type,
    },
    '/api/student/performance/save-score')
    .then(function(data){
      if (data.success) {
        bootbox.alert(data.message);
        $scope.studentRecord.status = type;
        for (var i = 0; i < $scope.students.length; i++) {
          if($scope.students[i].id == $scope.studentRecord.student_id){
            $scope.students[i].status = type;
          }
        }
        $scope.processing = false;
      } else {
        bootbox.alert(data.message);
        $scope.processing = false;
      }
    });
  }

  $scope.updateRecord = function(score){
    DBService.postCall({id:score.id,score:score.parameter},
      '/api/student/performance/update-score')
    .then(function(data){
        if (data.success) {
          $scope.getStudentRecord($scope.filterData.group_id)
          alert(data.message);
        }else{
          alert(data.message);
        }
    });
  } 

  $scope.getSessionList = function(){
    DBService.getCall("/api/student/performance/get-session-list").then(function(data){
        if(data.success) {
          $scope.sessionList = data.sessionList;
          $scope.loading = false;
        }
    });
  }

  $scope.deleteSession =  function(id){
    bootbox.confirm("are you sure",(check)=> {
        if (check) {
            DBService.getCall("/api/student/performance/delete-session/"+id)
            .then(function(data){
                if (data.success) {
                    bootbox.alert(data.message);
                    $scope.getSessionList();
                }else{
                    bootbox.alert(data.message);
                }
            });
        }
    });
  }

  $scope.saveSession =  function(){
    $scope.processing = true;
    DBService.postCall($scope.session,"/api/student/performance/add-session").then(function(data){
        if (data.success) {
            bootbox.alert(data.message);
            $scope.getSessionList();
            $scope.processing = false;
            $("#session_modal").modal('hide');
        }else{
            bootbox.alert(data.message);
            $scope.processing = false;
        }
    });
  }

  $scope.addSession = function(){
    $scope.session = {};
    $("#session_modal").modal('show');
  }

  $scope.editSession = function(session){
    $scope.session = session;
    $("#session_modal").modal('show');
  }

  $scope.hide_data_modal = function(id){
    $("#"+id).modal('hide');
  }


});
