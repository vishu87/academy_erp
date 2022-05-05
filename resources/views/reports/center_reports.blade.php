@extends('layout')

@section('sub_header')
	<div class="sub-header">
		<div class="table-div full">
			<div>
				<h4 class="fs-18 bold" style="margin:0;">Center Reports</h4>
			</div>
			<div class="text-right">
				
			</div>
		</div>
	</div>
@endsection

@section('content')

<div ng-controller='CenterReportController' class="ng-cloak" ng-init="getRevenueReports()">
	<div class="portlet">
		<div class="portlet-body">
		    <div>
		        <div class="table-div">
		            <div class="pad">
		                <a href="javascript:;" ng-click="getGraphDataType(1)" ng-class="graph.type == 1?'active':''">Week</a>
		            </div>
		            <div class="pad">
		                <a href="javascript:;" ng-click="getGraphDataType(2)" ng-class="graph.type == 2?'active':''">Month</a>
		            </div>
		            <div class="pad">
		                <a href="javascript:;" ng-click="getGraphDataType(3)" ng-class="graph.type == 3?'active':''">Year</a>
		            </div>
		            <div class="pad">
		                <input type="text" ng-model="graph.start_date" placeholder="Start Date" class="datepicker form-control compact">
		            </div>
		            <div class="pad">
		                <input type="text" ng-model="graph.end_date" placeholder="End Date" class="datepicker form-control compact">
		            </div>
		            <div class="pad">
		                <button class="btn btn-primary compact" ng-click="getGraphDataFilter()">Go</button>
		            </div>
		        </div>
		    </div>

		    <div style="height: 300px">
		        <div e-bar-chart dataid="data_points" datagraph = "data_points" ng-if="data_points.length > 0"></div>
		    </div>
		    <hr>
		    <div>
		        <a href="javascript:;" ng-click="selectAll()">Select All</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:;" ng-click="unSelectAll()">Unselect All</a>&nbsp;&nbsp;
		        &nbsp;&nbsp;&nbsp;&nbsp;Selected Centers - @{{center_ids.length}}&nbsp;&nbsp;&nbsp;&nbsp;
		        <button class="btn btn-primary" ng-click="getGraphData()"> Refresh Graph</button>
		    </div>


		    <div style="background:#EEE; padding:5px; cursor: pointer; margin:20px 0 10px 0; " ng-click="city.show_center = !city.show_center" >
		        <div class="row-item">
		            All Cities
		        </div>
		        
		        <div class="row-item">
		            Last 30D <span>@{{combined_data.monthly_show}}</span>
		        </div>
		        <div class="row-item">
		            LTM <span>@{{combined_data.ltm_show}}</span>
		        </div>
		        <div class="row-item">
		            YTD <span>@{{combined_data.ytd_show}}</span>
		        </div>
		        <div class="row-item">
		            Active Students <span>@{{combined_data.active_students}}</span>
		        </div>
		        <div class="row-item">
		            Coaches <span>@{{combined_data.coaches}}</span>
		        </div>
		        <div class="row-item">
		            Coordinator <span>@{{combined_data.coordinator}}</span>
		        </div>
		        <div class="row-item">
		            Ratio <span>1 : @{{combined_data.ratio}}</span>
		        </div>
		    </div>

		    <div ng-show="records.length > 0 && !loading" style="margin-top: 20px;">
		    	<div ng-repeat="city in records">
		            <div style="background:#EEE; padding:5px; cursor: pointer; margin-bottom: 10px; " ng-click="city.show_center = !city.show_center" >
		                <div class="row-item">
		                    @{{city.city_name}}
		                </div>
		                
		                <div class="row-item">
		                    Last 30D <span>@{{city.monthly_show}}</span>
		                </div>
		                <div class="row-item">
		                    LTM <span>@{{city.ltm_show}}</span>
		                </div>
		                <div class="row-item">
		                    YTD <span>@{{city.ytd_show}}</span>
		                </div>
		                <div class="row-item">
		                    Active Students <span>@{{city.active_students}}</span>
		                </div>
		                <div class="row-item">
		                    Coaches <span>@{{city.coaches}}</span>
		                </div>
		                <div class="row-item">
		                    Coordinator <span>@{{city.coordinator}}</span>
		                </div>
		                <div class="row-item">
		                    Ratio <span>1 : @{{city.ratio}}</span>
		                </div>
		            </div>
		            <div ng-show="city.show_center">
		                <div style="font-size: 10px">
		                    <a href="javascript:;" ng-click="selectAllCity(city.id)">Select Centers</a> &nbsp;&nbsp;|&nbsp;&nbsp;
		                    <a href="javascript:;" ng-click="unSelectAllCity(city.id)">Unselect Centers</a>&nbsp;&nbsp;
		                </div>

		                <table class="table table-compact table-hover">
		                    <thead>
		                        <tr>
		                            <th style="width:100px">
		                                
		                            </th>
		                            <th style="width:250px">Center</th>
		                            <th style="width:100px">Monthly Revenue</th>
		                            <th style="width:100px">LTM</th>
		                            <th style="width:100px">YTD</th>
		                            <th style="width:100px">Active Students</th>
		                            <th style="width:100px">Coaches</th>
		                            <th style="width:100px">Coordinators</th>
		                            <th >Ratio</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        <tr ng-repeat="record in city.centers">
		                            <td>
		                                <input type="checkbox" ng-click="selected_centers('center',record.id)" ng-checked="center_ids.indexOf(record.id) != -1 ? true: false ">
		                            </td>
		                            <td ng-click="centerDetails(record)">
		                                @{{record.center_name}}
		                                <a href="javascript:;" ng-click="getCenterInfo(record.center_name,record.id)" style="color:#888; font-size: 80%">Details</a>
		                            </td>
		                            <td>@{{record.monthly_revenue_show}}</td>
		                            <td>@{{record.ltm_show}}</td>
		                            <td>@{{record.ytd_show}}</td>
		                            <td>@{{record.active_students}}</td>
		                            <td>@{{record.coaches}}</td>
		                            <td>@{{record.coordinator}}</td>
		                            <td>1 : @{{record.ratio}}</td>
		                        </tr>

		                    </tbody>
		                </table>
		            </div>
		        </div>
		    </div>

		    <div  ng-show="loading" style="text-align: center;">
		    	<h2 class="page-title">Loading...</h2>
		    </div>
    	</div>
    </div>

    <div class="modal fade in" id="centerDetails" role="dialog">
        <div class="modal-dialog modal-big">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@{{open_center.center_name}} <a href="manage.php?type=centeredit&id=@{{open_center.id}}" target="_blank"><img src="edit.png" style="width: 13px;"></a></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body small-form" style="max-height:500px; overflow-y:auto">
                    <table class="table table-hover table-compact table-bordered" ng-if="!center_loading">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Group Name</th>
                                <th>Active Strength</th>
                                <th>Coaches</th>
                                <th>Ratio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="group in open_center.groups">
                                <td>@{{$index+1}}</td>
                                <td>@{{group.group_name}}</td>
                                <td>@{{group.active_students}}</td>
                                <td>@{{group.coaches}}</td>
                                <td>@{{group.ratio}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection


@section('footer_scripts')
<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/center_report_controller.js?v='.env('JS_VERSION'))}}" ></script>

@endsection