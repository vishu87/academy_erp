app.controller('Students_controller', function($scope, $http, DBService, Upload){
  
  $scope.loading = false;
  $scope.dataset = [];
  $scope.params = [];
  $scope.filter = {
    page_no : 1,
    max_per_page : 20,
    max_page: 1,
    order_by: '',
    order_type: 'ASC',
    export: false,
    show: false,
    pending_renewal: false,
    status: [0]
  }
  $scope.total = 0;
    $scope.myImage = '';
    $scope.myCroppedImage = '';

  $scope.init = function(){
    $scope.getList();
  }

  $scope.getList = function(){
    $scope.loading = true;

    DBService.postCall($scope.filter,'/api/student/get-list')
    .then(function(data){
      if (data.success) {
        if($scope.filter.export){
          window.open(data.excel_link,'_blank');
        } else {
          $scope.dataset = data.students;
          $scope.total = data.total;
          $scope.filter.max_page = Math.ceil($scope.total/$scope.filter.max_per_page)
        }
      } else {
        bootbox.alert(data.message);
      }
      $scope.loading = false;
      $scope.filter.searching = false;
      $scope.filter.clearing = false;
      $scope.filter.exporting = false;
      $scope.filter.export = false;
      $scope.setPagination();
    });
  }

  $scope.getParams = function(){
    $scope.getStateCityCenter('st-profile')
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

  $scope.getParams();

  $scope.searchList = function(){
    $scope.filter.page_no = 1;
    $scope.filter.searching = false;
    $scope.getList();
  }

  $scope.clear = function(){
    $scope.filter = {
      page_no : 1,
      max_per_page : 20,
      max_page: 1,
      clearing : true
    };
    $scope.getList();
  }

  $scope.exportList = function(){
    $scope.exporting = true;
    $scope.filter.export = true;
    $scope.getList();
  }

  // $scope.selectCroppedChangePic = function () {
  //     $scope.uploading = true;
  //     name = 'photo';
  //     var url = base_url+'/upload/photo';
  //     Upload.upload({
  //         url: url,
  //         data: {
  //           photo: Upload.dataUrltoBlob($scope.myCroppedImage, name),
  //           resize: 1,
  //           crop: 1,
  //           width: 400,
  //           height: 400
  //         },
  //     }).then(function (response) {
  //         if(response.data.success){
  //             $scope.myImage='';
  //             $scope.myCroppedImage='';

  //             $scope.changeProfilePicture(response.data.path);
  //             $scope.student.pic = response.data.url;
  //             $("#ChangeplayerPhoto").modal("hide");

  //         } else {
  //             bootbox.alert(data.message);
  //         }
  //         $scope.uploading = false;

  //     }, function (response) {
  //         if (response.status > 0) $scope.errorMsg = response.status 
  //             + ': ' + response.data;
  //         $scope.uploading = false;
  //     }, function (evt) {
  //         $scope.progress = parseInt(100.0 * evt.loaded / evt.total);
  //     });
  // }

  // $scope.changeProfilePicture = function(picture){
  //   DBService.postCall({
  //     id : $scope.student.id,
  //     picture : picture
  //   },'/students/change-profile-pic')
  //   .then(function(data){
  //       if (data.success) {
  //         bootbox.alert(data.message);
  //       }else{
  //         bootbox.alert(data.message);
  //       }
  //   });
  // }

  // var handleFileSelect=function(evt) {
  //   var file=evt.currentTarget.files[0];
  //   var reader = new FileReader();
  //   reader.onload = function (evt) {
  //     $scope.$apply(function($scope){
  //       $scope.myImage = evt.target.result;
  //     });
  //   };
  //   reader.readAsDataURL(file);
  // };

  // angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);

});

