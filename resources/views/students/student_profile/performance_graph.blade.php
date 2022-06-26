<div class="portlet">
  <div class="portlet-head">
    <div class="table-div full">
      <div>
        <ul class="menu">
          <li class="active">
            <a href="" >Performance Matrix</a>
          </li>
        </ul>
      </div>
      <div class="text-right">
        <select ng-model="performance_category_id" ng-change="getPerformanceGraph()" convert-to-number>
          <option value="0">Overall</option>
          <option value="@{{ cat.value }}" ng-repeat="cat in p_categories">@{{ cat.label }}</option>
        </select>
      </div>
    </div>
  </div>
  <div class="portlet-body" style="min-height: 300px;">
    <div ng-if="loading_graph" class="text-center mt-5">
      <div class="spinner-grow" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <div e-line-chart dataid="p_graph" datagraph="p_data" ng-if="p_data.legends.length > 0"></div>
    <div class="alert alert-warning" ng-if="p_data.legends.length == 0 && !loading_graph">
      Data is not available
    </div>
  </div>
  
</div>