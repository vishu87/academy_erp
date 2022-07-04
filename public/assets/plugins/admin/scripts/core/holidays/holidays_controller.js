app.controller("holidays_controller", function($scope, $http, DBService) {
    $scope.loading = true;
    $scope.processing = false;
    $scope.formData = {};
    $scope.attrData = {};

    $scope.init = function(){
      DBService.getCall('/api/holidays/init').then(function(data){
        $scope.holidays = data.holidays;
        $scope.loading = false;
      });
    }  

    $scope.add = function(){
      $scope.formData = {};
      $("#holiday-type-modal").modal('show');
    }

    $scope.submit = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData,'/api/holidays/save').then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
            $("#holiday-type-modal").modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  
    }

    $scope.edit = function(holiday){
      $scope.formData = {};
      $scope.formData = JSON.parse(JSON.stringify(holiday));
      $("#holiday-type-modal").modal('show');
    }

    $scope.delete = function(id, index){

      bootbox.confirm("Are you sure?", (check)=>{
      if (check) {
        $scope.processing = true;
        DBService.getCall('/api/holidays/delete/'+id).then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.holidays.splice(index,1);
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  

      }
      });
    }
});
