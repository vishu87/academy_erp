app.controller("payments_type_category_controller", function($scope, $http, DBService) {
	
	$scope.sport_id = 0;

	$scope.pay_cat_list = {};
	$scope.add_cat_item = {};
	$scope.edit_cat_item = {};
	$scope.Category = {};
	$scope.loading = true;
	$scope.processing = false;

	$scope.init = function(){
		DBService.postCall({
			sport_id : $scope.sport_id
		},"/api/pay-type-category/list")
		.then(function(data){
			if (data.success) {
				$scope.pay_cat_list = data;
				$scope.loading = false;
			}
		});
	}

	$scope.add = function(){
		$scope.processing = true;
		$scope.add_cat_item.sport_id = $scope.sport_id;
		DBService.postCall(
			$scope.add_cat_item
			,"/api/pay-type-category/add")
		.then(function(data){
			if (data.success) {
			    $('#add_pay_modal').modal('hide');
				$scope.init();
				bootbox.alert(data.message);
				$scope.processing = false;
				$scope.add_cat_item = {};
			}else{
				bootbox.alert(data.message);
				$scope.processing = false;
			}
		});
	}

	$scope.addCategory = function(){
		$scope.processing = true;
		$scope.Category.sport_id = $scope.sport_id;
		DBService.postCall(
			$scope.Category
			,"/api/pay-type-category/add-category"
		)
		.then(function(data){
			if (data.success) {
			    $('#add_category_modal').modal('hide');
			    $('#edit_category_modal').modal('hide');
				$scope.init();
				$scope.processing = false;
				bootbox.alert(data.message);
			}else{
				$scope.processing = false;
				bootbox.alert(data.message);
			}
		});	
	}

	$scope.update = function(item){
		$scope.updateProcessing = true;
		DBService.postCall(item,"/api/pay-type-category/update")
		.then(function(data){
			if (data.success) {
				$scope.hide_data_modal('edit_category_item');
				bootbox.alert(data.message);
				$scope.init();
				$scope.updateProcessing = false;
			}else{
				bootbox.alert(data.message);
				$scope.updateProcessing = false;
			}
		});
	}

	$scope.delete = function(id){
		bootbox.confirm("Are you sure?", (check)=>{
			if (check) {
				$scope.deleteProcessing = true;
				DBService.postCall({ id : id },"/api/pay-type-category/delete")
				.then(function(data){
					if (data.success) {
						bootbox.alert(data.message);
						$scope.hide_data_modal('edit_category_item');
						$scope.init();
						$scope.deleteProcessing = false;
					}else{
						bootbox.alert(data.message);
						$scope.deleteProcessing = false;
					}
				});
			}
		});
	}

	$scope.disableCategory = function(){
		bootbox.confirm("Are you sure?", (check)=>{
			if (check) {
				$scope.disableProcessing = true;
				DBService.postCall({ id : $scope.Category.id},"/api/pay-type-category/disable-category")
				.then(function(data){
					if (data.success) {
					    $('#edit_category_modal').modal('hide');
						$scope.init();
						$scope.disableProcessing = false;
						bootbox.alert(data.message);
					}else{
						$scope.disableProcessing = false;
						bootbox.alert(data.message);
					}
				});
			}
		});

	}

	$scope.deleteCategory = function(){
		bootbox.confirm("Are you sure?", (check)=>{
			if (check) {
				$scope.deleteProcessing = true;
				DBService.postCall({ id : $scope.Category.id},"/api/pay-type-category/delete-category")
				.then(function(data){
					if (data.success) {
					    $('#edit_category_modal').modal('hide');
						$scope.init();
						$scope.deleteProcessing = false;
						bootbox.alert(data.message);
					}else{
						$scope.deleteProcessing = false;
						bootbox.alert(data.message);
					}
				});
			}
		});
			
	}

	$scope.editCategoryModal = function(item){
		$scope.Category = item;
	    $('#edit_category_modal').modal('show');
	}

	$scope.addPayCategory = function(){
		$scope.Category = {};
	    $('#add_category_modal').modal('show');
	}

	$scope.addPaySubCategory = function(id, is_sub_type){
		$scope.is_sub_type = is_sub_type;
		$scope.add_cat_item.category_id = id;
	    $('#add_pay_modal').modal('show');
	}

	$scope.show_data_modal = function(id, type_data){
		console.log(type_data);
		$scope.edit_cat_item = type_data;
	    $('#'+id).modal('show');
	}

	$scope.hide_data_modal = function(id){
	     $('#'+id).modal('hide');
	}


});