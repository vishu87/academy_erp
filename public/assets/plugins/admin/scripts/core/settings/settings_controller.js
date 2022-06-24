app.controller("SettingsController", function($scope, $http, DBService, Upload) {
    
    $scope.switchContent = 'general';
    $scope.items = [];

    $scope.init = function(){
      $scope.loading = true;
      DBService.postCall(
        {
          category : $scope.switchContent
        },
        "/api/settings/init"
      )
      .then(function (data){  
        if (data.success) {
          $scope.items = data.items;
        }

        $scope.loading = false;
      });
    }

    $scope.switchContentType = function(type){
      $scope.switchContent = type;
      $scope.init();
    }


    $scope.uploadImage = function(file, item){

      item.uploading = true;
      name = 'photo';
      item.progress = 0;

      var url = base_url+'/upload/photo';

      Upload.upload({
        url: url,
        data: {
          photo: file
        }
      }).then(function (resp) {

        if(resp.data.success){
          item.value = resp.data.url;
        } else {
          alert(resp.data.message);
        }
        item.uploading = false;
      }, function (resp) {
          item.uploading = false;
      }, function (evt) {
          item.progress = parseInt(100.0 * evt.loaded / evt.total) + '%';
      });
    }

    $scope.removeImage = function(item){
      item.value = "";
    }

    $scope.saveSettings = function(){
      $scope.processing = true;
      DBService.postCall(
        {
          items : $scope.items
        },
        "/api/settings/save"
      )
      .then(function (data){  
        $scope.processing = false;
      });
    }

    $scope.openEditor = function(item){
      $scope.editor_id = item.id;
      $scope.editor_data = item.value ? item.value : "";
      $scope.show_editor = true;
      $("#editor_modal").modal("show");
    }

    $scope.closeEditorModal= function(){
        $scope.show_editor = false;
        $("#editor_modal").modal("hide");   
    }

    $scope.saveEditorModal= function(){
        
        for (var i = 0; i < $scope.items.length; i++) {
          if($scope.items[i].id == $scope.editor_id){
            $scope.items[i].value = window.editor.getData();
          }
        }

        $scope.show_editor = false;
        $("#editor_modal").modal("hide");   
    }

    $scope.setData = function(content){
        $scope.$apply(() => {
            $scope.editor_data = content;
        })
    }

});
