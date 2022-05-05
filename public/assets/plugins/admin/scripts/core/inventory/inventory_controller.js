app.controller("inventory_controller", function($scope, $http, DBService) {
	$scope.units = {};
	$scope.addUnits = {};
	$scope.items = {};
	$scope.edit_items = false;


	$scope.getUnits = function(){
		DBService.postCall({},"/api/inventory/get-units")
		.then(function(data){
			console.log(data);
			$scope.units = data.units;
		});
	}

	$scope.init = function(){
		$scope.getUnits();
		DBService.postCall({},"/api/inventory/items-list")
		.then(function(data){
			if (data.success) {
				$scope.items = data.items;
			}
		});
	}

	$scope.add = function(item){
		item.id = 0;
		console.log(item);
		DBService.postCall({item:item},"/api/inventory/add-items")
		.then(function(data){
			if (data.success) {
				alert(data.message);
				$scope.init();
			}else{
				alert(data.message);
			}
		});
	}

	$scope.delete = function(id){
		if (confirm("Are you sure want to delete")) {
			DBService.postCall({id:id},"/api/inventory/delete-items")
				.then(function(data){
					if (data.success) {
						alert(data.message);
						$scope.init();
					}else{
						alert(data.message);
					}
				});
		}
	}

	$scope.edit = function(item){
		$scope.edit_items = true;
		$scope.addUnits = item;
		if (!$scope.addUnits.show) {
			$scope.addUnits.show = true;
		}
		window.scroll(0,0);
	}

	$scope.update = function(item){
		DBService.postCall({item:item},"/api/inventory/add-items")
			.then(function(data){
				if (data.success) {
					alert(data.message);
					$scope.init();
					$scope.edit_items = false;
					$scope.addUnits = {};
				}else{
					alert(data.message);
				}
			});
	} 

});