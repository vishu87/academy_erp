@extends('layout')

@section('content')
<div class="" ng-controller="payments_type_category_controller" ng-init="sport_id = 1; init(); ">
	@include('payments.pay_type_category.category_list_modal')

	<div ng-if="loading" class="text-center mt-5 mb-5">
      <div class="spinner-grow" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
		
	<div class="portlet" ng-if="!loading">
		<div class="portlet-head">
			<div class="row">

				<div class="col-md-6 col-6">
					<ul class="menu">
						<li class="active">
							<a href="#">Payment Categories</a>
						</li>
					</ul>
				</div>

				<div class="col-md-6 col-6 text-right">
					<button class="btn btn-primary" ng-click="addPayCategory()"><i class="icons icon-plus"></i> Add Category</button>
				</div>

			</div>
		</div>

		<div class="portlet-body ng-cloak">
			
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr class="">
							<th>SN</th>
							<th>Category</th>
							<th>Sub Category</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="cat in pay_cat_list.pay_type_cat track by $index" ng-class="cat.inactive == 1 ? 'opaque' : ''">
							<td>@{{$index + 1}}</td>
							<td>
								@{{cat.category_name}} <button class="btn btn-sm btn-light" ng-click="editCategoryModal(cat)" ng-if="!cat.locked">Edit</button>
							</td>
							<td ng-if="cat.inactive == 0">
								<button class="btn btn-light p-1 m-1" ng-repeat="type in pay_cat_list.pay_type" ng-if="type.category_id == cat.id" ng-click="show_data_modal('edit_category_item', type)">
									@{{type.name}}
								</button>
								<button type="button" class="btn btn-primary" ng-click="addPaySubCategory(cat.id, cat.is_sub_type)">
									<i class="icon-plus icons "></i> Add
								</button>
							</td>
							<td ng-if="cat.inactive == 1"></td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>

</div>

@endsection
