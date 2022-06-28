app.controller('CenterReportController',function($scope, $timeout , $http, $timeout, DBService, Upload){
  $scope.searchData = {};
  $scope.records = [];
  $scope.loading = true;
  $scope.center_ids = [];
    $scope.data_points = [];
    $scope.prr_type = 1;

    $scope.graph = {
      type : 1,
      start_date : "",
      end_date : ""
    };

    $scope.centerDetails = function(record){
      $scope.open_center  = [];
      $scope.open_center = record;
    $("#centerDetails").modal("show");
    $scope.center_loading = true;
      DBService.postCall(record , '/api/center/getCenterInfo').then(function(data){
        if(data.success){
          $scope.open_center.groups = data.groups;
        }
        $scope.center_loading = false;
      });
    }
     
    $scope.selected_centers = function(type, center_id){
      if(type == 'center'){
        var idx = $scope.center_ids.indexOf(center_id);

        if(idx == -1) $scope.center_ids.push(center_id);
        else $scope.center_ids.splice(center_id,1);
      }
    }

    $scope.getRevenueReports = function(){
      $scope.searchData.api_key = api_key;
      $scope.center_ids = [];
      DBService.postCall($scope.searchData,'/api/reports/revenueReport').then(function(data){
        if(data.success){
          $scope.records = data.records;

          $scope.combined_data = data.combined_data;

          $scope.revenue_month = data.revenue_month;
          $scope.annual_period = data.annual_period;
          for (var i = 0; i < $scope.records.length; i++) {
            for (var j = 0; j < $scope.records[i].centers.length; j++) {
              $scope.center_ids.push($scope.records[i].centers[j].id);
            };
          };

          $timeout($scope.getGraphData(), 1000);

        }
        
        $scope.loading =false;
      });
    }

    $scope.getCenterInfo = function(center_name, center_id){
      console.log(center_name,center_id);
    }

    $scope.getGraphDataType = function(type){
      $scope.graph.start_date = "";
      $scope.graph.end_date = "";
      $scope.graph.type = type;
      $scope.getGraphData();
    }

    $scope.getGraphDataFilter = function(){

      if($scope.graph.start_date == "") {
        alert("Please select start date");
        return;
      }
      if($scope.graph.end_date == "") {
        alert("Please select end date");
        return;
      }

      $scope.graph.type = 4;

      $scope.getGraphData();

    }

    $scope.getGraphData = function(){

      if($scope.center_ids.length == 0){
        alert("Please select at least one center");
        return;
      }

      $scope.data_points = [];
      DBService.postCall({center_ids: $scope.center_ids, graph:$scope.graph},'/api/reports/center/revenue').then(function(data){
        
        $scope.data_points = data.data_points;
      });
    }

    $scope.selectAll = function(){
      $scope.center_ids = [];
      for (var i = 0; i < $scope.records.length; i++) {
      for (var j = 0; j < $scope.records[i].centers.length; j++) {
        $scope.center_ids.push($scope.records[i].centers[j].id);
      };
    };
    }

    $scope.unSelectAll = function(){
      $scope.center_ids = [];
    }

    $scope.selectAllCity = function(city_id){
      for (var i = 0; i < $scope.records.length; i++) {
      if($scope.records[i].id == city_id){
        for (var j = 0; j < $scope.records[i].centers.length; j++) {
          var idx = $scope.center_ids.indexOf($scope.records[i].centers[j].id);
          if(idx == -1){
            $scope.center_ids.push($scope.records[i].centers[j].id);  
          }
        };
      }
    };
    }

    $scope.unSelectAllCity = function(city_id){
      for (var i = 0; i < $scope.records.length; i++) {
      if($scope.records[i].id == city_id){
        for (var j = 0; j < $scope.records[i].centers.length; j++) {
          var idx = $scope.center_ids.indexOf($scope.records[i].centers[j].id);
          if(idx > -1){
            $scope.center_ids.splice(idx,1);
          }
        };
      }
    };
    }

    $scope.getPRRReports = function(){
      $scope.loading = true;
      $scope.searchData.prr_type = $scope.prr_type;
      DBService.postCall($scope.searchData,'/api/center/PRRReport').then(function(data){
        if(data.success){
          $scope.records = data.records;
          $scope.total_records = data.total;
        }
        $scope.loading =false;
      });
    }

    $scope.getPricePackageReports = function(){
      DBService.postCall($scope.searchData,'/api/center/getPricePackageReports').then(function(data){
        if(data.success){
          $scope.records = data.records;
        }
        $scope.loading =false;
      });
    }
});