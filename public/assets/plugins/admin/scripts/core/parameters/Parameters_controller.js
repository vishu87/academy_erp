app.controller("Parameters_controller", function($scope, $http, DBService) {
    
    $scope.group_type_id = 0;
    $scope.sport_id = 0;
    $scope.loading = true;
    $scope.processing = false;
    $scope.formData = {};
    $scope.attrData = {};
    $scope.groupSkillAttribute = {};
    $scope.skillAttributeId = [];

    $scope.init = function(){
      DBService.getCall('/api/parameters/get-parameters/'+$scope.sport_id)
      .then(function(data){
        $scope.parameters = data.skill_categories;
        $scope.groupTypes();
        $scope.loading = false;
      });
    }  

    $scope.groupTypes = function(){
      DBService.getCall('/api/parameters/get-group-types')
      .then(function(data){
        $scope.group_types = data.group_types;
      });
    }  

    $scope.addCategory = function(){
      $("#category-modal").modal('show');
    }

    $scope.saveCategory = function(){
        $scope.processing = true;
        $scope.formData.sport_id = $scope.sport_id;
        DBService.postCall($scope.formData,'/api/parameters/save-category').then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
            $("#category-modal").modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  
    }

    $scope.editCategory = function(category){
      $scope.formData = JSON.parse(JSON.stringify(category));
      $("#category-modal").modal('show');
    }

    $scope.deleteCategory = function(id){

      bootbox.confirm("Are you sure?", (check)=>{
      if (check) {
        $scope.processing = true;
        DBService.getCall('/api/parameters/delete-category/'+id).then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  

      }
      });
    }

    $scope.addAttribute = function(parameterId){
      $scope.attrData = {};
      $scope.parameter_id = parameterId;
      $("#attribute-modal").modal('show');
    }

    $scope.saveAttribute = function(){
      $scope.attrData.category_id = $scope.parameter_id;
        $scope.processing = true;
        DBService.postCall($scope.attrData,'/api/parameters/save-attribute').then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
            $("#attribute-modal").modal('hide');
          } else {
            bootbox.alert(data.message);
          }
          $scope.processing = false;
        });  
    }

    $scope.editAttribute = function(attr){
      $scope.attrData = JSON.parse(JSON.stringify(attr));
      $("#attribute-modal").modal('show');
    }

    $scope.deleteAttribute = function(id){

      bootbox.confirm("Are you sure?", (check)=>{
      if (check) {
        
        $scope.processing = true;
        DBService.getCall('/api/parameters/delete-attribute/'+id).then(function(data){
          if(data.success){
            bootbox.alert(data.message);
            $scope.init();
          }
          $scope.processing = false;
        });

      }
      });
    }

    $scope.toggleAttr = function(att){
      $scope.groupSkillAttribute.skill_attribute_id = att.id;
      $scope.groupSkillAttribute.group_type_id = $scope.group_type_id;
      DBService.postCall($scope.groupSkillAttribute,'/api/parameters/save-group-skill-attribute').then(function(data){
        if(data.success){
          att.value = data.value;
        }
      });
    }

    $scope.changeGroup = function(){
      if($scope.group_type_id == 0) return;
      DBService.getCall('/api/parameters/get-group-skill-attribute/'+$scope.group_type_id).then(function(data){
        if(data.success){
          ids = data.skillAttributeIds;

          for (var i = 0; i < $scope.parameters.length; i++) {
            for (var j = 0; j < $scope.parameters[i].attributes.length; j++) {
              console.log(ids.indexOf($scope.parameters[i].attributes[j].id));
              if( ids.indexOf($scope.parameters[i].attributes[j].id) > -1 ){
                $scope.parameters[i].attributes[j].value = 1;
              } else {
                $scope.parameters[i].attributes[j].value = 0;
              }
            }
          }

        }
      });
    }
});
