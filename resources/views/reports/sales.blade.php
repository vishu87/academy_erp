@extends('layout')

@section('sub_header')
	<div class="sub-header">
		<div class="table-div full">
			<div>
				<h4 class="fs-18 bold" style="margin:0;">{{$name}}</h4>
			</div>
			<div class="text-right">
				
			</div>
		</div>
	</div>
@endsection

@section('content')

<style type="text/css">
    .city {
        margin-bottom: 15px;
    }
    #right1 table .city-row {
        background: #EEE;
        cursor: pointer;
    }
    #right1 table .city-row td {
        font-size: 14px;
        color: #b33225;
        cursor: pointer;
    }
    .t-r {
        text-align: right;
    }

    #right1 table .all-india-row {
        background: #EEE;
        cursor: pointer;
    }
    #right1 table .all-india-row td {
        font-size: 12px;
        color: #b33225;
        cursor: pointer;
    }

</style>

<div ng-controller='SalesDashboardCtrl' class="ng-cloak" ng-init="applyFilter()" id="right1">
    <div class="row">
        <div class="col-md-2">
            <label>Report Type</label>
            <select class="form-control" id="select_box" ng-model="filter.report_type" ng-change="applyFilter()">
                <option value="">Select</option>
                <?php $first_rep = "" ?>
                @foreach($types as $key => $value)
                <?php if(!$first_rep) $first_rep = $key; ?>
                <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Month</label>
            <select class="form-control" ng-model="filter.month" ng-change="applyFilter()">
                <option value="">Select</option>
                <option value="01">Jan</option>
                <option value="02">Feb</option>
                <option value="03">Mar</option>
                <option value="04">Apr</option>
                <option value="05">May</option>
                <option value="06">Jun</option>
                <option value="07">Jul</option>
                <option value="08">Aug</option>
                <option value="09">Sep</option>
                <option value="10">Oct</option>
                <option value="11">Nov</option>
                <option value="12">Dec</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Year</label>
            <select class="form-control" ng-model="filter.year" ng-change="applyFilter()">
                <option value="">Select</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
                <option value="2019">2019</option>
                <option value="2018">2018</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>End Date Ref</label>
            <input type="text" ng-model="filter.date_ref" class="datepicker form-control">
        </div>
        <div class="col-md-2 ">
            <label>&nbsp;</label>
            <div>
                <button type="button" ng-click="applyFilter()" class="btn btn-primary">Go</button>
            </div>
        </div>
        <div class="col-md-2 text-right">
            <label>&nbsp;</label>
            <form action="admin-api/api/general-export" method="POST" id="export_form" target="_blank">
                <input type="hidden" name="data_type" />
                <input type="hidden" name="html_data" />
                <button type="button" id="btn_submit" class="btn btn-primary">Export</button>
            </form>
        </div>
    </div>

    <hr>

    <div ng-if="processing">Loading data</div>
    <div class="portlet">
    	<div class="portlet-body">
		    <div ng-if="!processing" id="data_val">

		        <div class="row" ng-if="layout == 0">
		            <div class="col-md-6">
		                <div class="city">
		                    <table class="table">
		                        <tr class="all-india-row">
		                            <td wid="25">
		                                All India
		                            </td>
		                            <td class="t-r">@{{all_india.value | INR }}</td>
		                        </tr>
		                    </table>
		                </div>

		                <div ng-repeat="city in cities" class="city">
		                    <table class="table">
		                        <thead>
		                            <tr class="city-row" ng-click="city.show = !city.show">
		                                <td wid="25">
		                                    @{{city.name}}
		                                </td>
		                                <td class="t-r">@{{city.value | INR }}</td>
		                            </tr>
		                        </thead>
		                        <tbody ng-hide="!city.show">
		                            <tr class="center-row" ng-repeat="center in city.centers">
		                                <td style="width:200px">@{{center.name}}</td>
		                                <td class="t-r">@{{center.value | INR }}</td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>
		            </div>
		        </div>

		        <div class="row" ng-if="layout == 1">
		            <div class="col-md-6">
		                <div>
		                    <table class="table">
		                        <thead>
		                            <tr class="city-row" ng-click="city.show = !city.show">
		                                <td wid="25">Name</td>
		                                <td class="t-r">Value</td>
		                            </tr>
		                        </thead>
		                        <tbody>
		                            <tr ng-repeat="member in members" ng-if="member.value > 0">
		                                <td style="width:200px">@{{member.name}}</td>
		                                <td class="t-r">@{{member.value | INR }}</td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>
		            </div>
		        </div>

		        <div class="row" ng-if="layout == 2">
		            <div class="col-md-12">

		                <div>
		                    <table class="table">
		                        <tr>
		                            <td  wid="25" style="min-width:150px"></td>
		                            <td class="t-r" ng-repeat="date in dates">@{{date | date}}</td>
		                            <td class="t-r" ng-repeat="week in weeks">W - @{{$index+1}}</td>
		                            <td class="t-r">Monthy</td>
		                        </tr>
		                        <tr class="all-india-row">
		                            <td>All India</td>
		                            <td class="t-r" ng-repeat="day in all_india.data.days">@{{day.value | INR }}</td>
		                            <td class="t-r" ng-repeat="week in all_india.data.weeks">@{{week.value | INR }}</td>
		                            <td class="t-r">@{{all_india.value | INR }}</td>
		                        </tr>
		                    </table>
		                </div>

		                <div ng-repeat="city in cities">
		                    <table class="table">
		                        <thead>
		                            <tr>
		                                <td  wid="25" style="min-width:150px"></td>
		                                <td class="t-r" ng-repeat="date in dates">@{{date | date}}</td>
		                                <td class="t-r" ng-repeat="week in weeks">W - @{{$index+1}}</td>
		                                <td class="t-r">Monthy</td>
		                            </tr>
		                            <tr class="city-row" ng-click="city.show = !city.show">
		                                <td>@{{city.name}}</td>
		                                <td class="t-r" ng-repeat="day in city.data.days">@{{day.value | INR }}</td>
		                                <td class="t-r" ng-repeat="week in city.data.weeks">@{{week.value | INR }}</td>
		                                <td class="t-r">@{{city.value | INR }}</td>
		                            </tr>
		                        </thead>
		                        <tbody ng-hide="!city.show">
		                            <tr ng-repeat="center in city.centers">
		                                <td style="width:200px">@{{center.name}}</td>
		                                <td class="t-r" ng-repeat="day in center.data.days">@{{day.value | INR }}</td>
		                                <td class="t-r" ng-repeat="week in center.data.weeks">@{{week.value | INR }}</td>
		                                <td class="t-r">@{{center.value | INR }}</td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>
		            </div>
		        </div>

		    </div>
    	</div>
    </div>

    <!-- <form action="admin-api/api/export-excel" method="post" target="_blank">
        <button type="submit">Export to excel</button>
    </form> -->

</div>

@endsection


@section('footer_scripts')

<script type="text/javascript">
	var report_type = '{{$first_rep}}';
</script>

<script type="text/javascript">
    $("#btn_submit").click(function(){
        console.log("asdasd");
        $("input[name=html_data]").val($("#data_val").html());
        
        $("input[name=data_type]").val($("#select_box option:selected").text());
        $("#export_form").submit();
    });
</script>

<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/sales_controller.js?v='.env('JS_VERSION'))}}" ></script>

@endsection