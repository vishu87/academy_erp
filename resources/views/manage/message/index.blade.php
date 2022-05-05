@extends('layout')

<div class="" ng-controller="communicationCtrl" ng-init="filter.only_active = $only_active; init()">
	@section('sub_header')
		<div class="sub-header">
			<div class="table-div full">
				<div>
					<h4 class="fs-18 bold" style="margin:0;">SMS Templates</h4>
				</div>
			</div>
		</div>
	@endsection

@section('content')
		
	<div class=" ng-cloak" style="clear: both;" >

		<div class="title-cont">
			<div class="row">
				<div class="col-md-6">
					<h2 class="page-title">Communications</h2>
					<!-- <a href="{{url('communications/send-message/init/?only_active=1')}}" style="text-decoration:underline; $only_active == 1 ?> font-weight:bold ">Active Centers/Groups</a> &nbsp; &nbsp; &nbsp; &nbsp;
					<a href="communications.php?only_active=0" style="text-decoration:underline; $only_active == 0 ?> font-weight:bold ">All Centers/Groups</a>
					 -->
					 <a href="#" ng-click="activeCenter(1)" style="text-decoration:underline; $only_active == 1 ?> font-weight:bold ">Active Centers/Groups</a> &nbsp; &nbsp; &nbsp; &nbsp;
					<a href="#" ng-click="activeCenter(0)" style="text-decoration:underline; $only_active == 0 ?> font-weight:bold ">All Centers/Groups</a>
				</div>
				
			</div>
		</div>
		
		
		<div class="container-fluid filters small-form">
			<form ng-submit="filterStudents()">
			
					
					<div class="row form-group" style="height: 250px; overflow-y:auto; background: #F8F8F8;"  >
						<div class="col-md-2 ">
			                <label>City</label>
			                <input type="checkbox"  ng-model="check_all_city" ng-click="check_all(1)"> Select All
			                <div ng-repeat="city in cities">
			                	<input type="checkbox" ng-checked="filter['cities'].indexOf(city.id) > -1" ng-click="addFilter('cities',city.id)"> @{{city.city_name}} <span ng-if="city.city_status == 1">(I)</span>
			                </div>
			            </div>
						<div class="col-md-2 ">
			                <label>Center</label>
			                <input type="checkbox"  ng-model="check_all_center" ng-click="check_all(2)"> Select All

			                <div ng-repeat="center in filter_centers">
			                	<input type="checkbox" ng-checked="filter['centers'].indexOf(center.id) > -1" ng-click="addFilter('centers',center.id)"> @{{center.center_name}} <span ng-if="center.center_status == 1">(I)</span>
			                </div>
			            </div>
			            <div class="col-md-2 ">
			                <label>Groups</label>
			                <input type="checkbox" ng-model="check_all_groups" ng-click="check_all(3)"> Select All

			                <div ng-repeat="group in filter_groups">
			                	<input  type="checkbox" ng-checked="filter['groups'].indexOf(group.id) > -1" ng-click="addFilter('groups',group.id)"> @{{group.group_name}} (@{{group.center_name}}) <span ng-if="group.group_status == 1">(I)</span>
			                </div>
			            </div>

			            <div class="col-md-2 ">
			                <label>Categories</label>
			                <input type="checkbox" ng-model="check_all_category" ng-click="check_all(4)"> Select All

			                <div ng-repeat="category in student_categories">
			                	<input type="checkbox" ng-checked="filter['categories'].indexOf(category) > -1" ng-click="addFilter('categories',category)"> @{{category}}
			                </div>
			            </div>

			            <div class="col-md-4 ">

			            	<div>
				                <label>Status</label>
				                <div>
				                	<input type="checkbox" ng-checked="filter['status'].indexOf(0) > -1" ng-click="addFilter('status',0)"> Active &nbsp;&nbsp;
				                	<input type="checkbox" ng-checked="filter['status'].indexOf(1) > -1" ng-click="addFilter('status',1)"> Inactive
				                </div>
			            	</div>
			            	<div style="margin-top: 10px">
				                <label style="display: block;">Batch Types</label>
				                <div ng-repeat="batch_type in batch_types" style="display: inline-block;">
				                	<input type="checkbox" ng-checked="filter['batch_types'].indexOf(batch_type.id) > -1" ng-click="addFilter('batch_types',batch_type.id)"> @{{batch_type.name}} &nbsp;&nbsp;&nbsp;
				                </div>
				            </div>

			            	<div style="margin-top:20px">
			            		<div class="row">
			            			<div class="col-md-6">
			            				<label>DOB Start Date</label>
						                <div style="margin-bottom:10px">
						                	<input type="text" ng-model="filter.date_start" class="form-control datepicker" ng-change="getStudents(1)" />
						                </div>		
			            			</div>
			            			<div class="col-md-6">
			            				<label>DOB End Date</label>
						                <div>
						                	<input type="text" ng-model="filter.date_end" class="form-control datepicker" ng-change="getStudents(1)" />
						                </div>	
			            			</div>
			            		</div>
			            	</div>

			                <div class="form-group ">
				                <label>Renewal Dates</label><br>
			            		<div class="row">
				                	<div class="col-md-6 form-group">
				                		<input type="text" placeholder="Min" ng-model="filter.min_renew_days" class=" form-control datepicker">
				                	</div>
				                	<div class="col-md-6 form-group">
				                		<input type="text" placeholder="Max" ng-model="filter.max_renew_days" class=" form-control datepicker">
				                	</div>
				                </div>
				            </div>

				            <div class="form-group">
				                <label>Paused?</label><br>
				                <label>
				                	<input type="radio" ng-model="filter.paused" value="1"> Yes &nbsp;
				                </label>
				                <label>
				                	<input type="radio" ng-model="filter.paused" value="2"> No &nbsp;
				                </label>
				                <label>
				                	<input type="radio" ng-model="filter.paused" value="0"> All &nbsp;
				                </label>
				            </div>

				            <div class="form-group">
				                <label>Downloaded App?</label><br>
				                <label>
				                	<input type="radio" ng-model="filter.downloaded_app" value="1"> Yes &nbsp;
				                </label>
				                <label>
				                	<input type="radio" ng-model="filter.downloaded_app" value="2"> No &nbsp;
				                </label>
				                <label>
				                	<input type="radio" ng-model="filter.downloaded_app" value="0"> All &nbsp;
				                </label>
				            </div>

				            <div class="form-group">
				                <label>Student Mobile <span style="font-style: italic;">put comma for multiple</span></label><br>
				                <input type="text"  ng-model="filter.mobile" class=" form-control">
				            </div>
			            	
			            </div>

			            <div class="col-md-2">

			            	<div style="margin:10px 0">
			            		<button class="btn btn-primary" ladda="loading" ng-click="getStudents(1)">Search</button>
			            	</div>

			            	<div>
			            		<div>Total Students Selected = @{{count}}  </div>
				            	<div>Total Students Removed = @{{removed_students.length}}</div>  
					            <div ng-if="students.length > 0 && !loading" style="margin-top: 10px">
									<button class="btn btn-primary" type="button" ng-click="sendMessage()">Send Message</button>
								</div>
			            	</div>

			            	
			            </div>

			            
					</div>
			</form>


			<div ng-if="!loading" style="margin-top: 20px">
				<div class="row">
					<div class="col-md-7">
						
				 		<button ng-if="students.length > 0" class="btn btn-sm btn-primary" type="button" ng-click="toggleList()">@{{show_list ? 'Hide':'Show'}} Selected Students</button>
					</div>

					<div class="col-md-5">
						<button ng-if="removed_students.length > 0" class="btn btn-sm btn-primary" type="button" ng-click="toggleRemovedList()">@{{show_removed_list ? 'Hide':'Show'}} Removed Students</button>
						
					</div>
				</div>
			</div>


			<div ng-show="loading" class="alert alert-warning container" style="margin-top: 50px">
				Loading...
			</div>
			<div ng-show="noDataFound" class="alert alert-danger container" style="margin-top: 50px">
				No Data Found
			</div>


			<div class="row">
				<div class="col-md-7">
					
					<div ng-show="show_list" style="margin-top: 10px;height: 300px;overflow-x: scroll;">

						<div class="row">
							<div class="col-md-6 text-left">
								Total - @{{count}} | Showing @{{ ((pn-1)*max + 1) + ' - ' }} @{{(pn*max < count) ? pn*max : count}}
							</div>
							<div class="col-md-6 text-right">
								<a href="javascript:;" ng-click="prevPage()">Prev</a>
									| @{{pn}} of @{{total_pn}} |
								<a href="javascript:;" ng-click="nextPage()">Next</a>

							</div>
						</div>
						
						<div class="ng-cloak" ng-show="students.length > 0 && !loading" style="overflow-y: auto;">
							<table class="table  table-compact table-bordered table-stripped">
								<tr>
									<th>SN</th>
									<th style="cursor: pointer;"  ng-click="sortBy('name')">Name <span ng-if=" sort_by == 'name' && sorting == 'ASC' "><i class="fa fa-angle-up"></i></span> <span ng-if=" sort_by == 'name' && sorting == 'DESC' "><i class="fa fa-angle-down"></i></span> </th>

									<th>DOB</th>
									<th>Subscription End</th>
									<th>Mobile</th>
									<th style="cursor: pointer;"  ng-click="sortBy('center_name')">Center <span ng-if=" sort_by == 'center_name' && sorting == 'ASC' "><i class="fa fa-angle-up"></i></span> <span ng-if=" sort_by == 'center_name' && sorting == 'DESC' "><i class="fa fa-angle-down"></i></span> </th>
									<th>Remove</th>
								</tr>
								
								<tbody>
									<tr ng-repeat="student in students">
										<td>@{{ (pn-1)*max + $index + 1}}</td>

										<td>
											<span style="display: block;">@{{student.name}}</span>
										</td>

										<td>
											@{{student.dob}}
										</td>
										<td>
											@{{student.doe}}
										</td>

										<td style="cursor: pointer;" ng-click="showNumber(student.father_mob)">
											<a href="javascript:;">
												@{{student.mobile_trimmed}}
											</a>
										</td>

										<td >
											@{{student.center_name}}
											
										</td>
										<td>
											<button type="button" class="btn btn-danger" ng-click="removeStudent(student,$index)" ladda="student.delete">X</button>
										</td>

									</tr>
								</tbody>
							</table>


							

							
						</div>


						
					</div>
				</div>
				<div class="col-md-5">
					

					<div ng-show="show_removed_list" style="margin-top: 10px">

						<span  class="btn btn-default" style="margin-right: 5px;margin-top: 5px" ng-repeat="student in removed_students">@{{student.name}} &nbsp;&nbsp;&nbsp;<button ng-click="addStudentToList(student,$index)" class="btn btn-info btn-xs">+</button></span>
					</div>
				</div>
			</div>


			
		</div>
					


		<div class="modal fade in" id="showNumber" role="dialog">
			<div class="modal-dialog modal-small">
			    <div class="modal-content">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			        <div class="modal-body">
			            <div style="font-size:32px;" class="text-center">
			                @{{mobile_show}}
			            </div>
			            
			        </div>
			    </div>
			</div>
		</div>

		<div class="modal fade in" id="messageForm" role="dialog" data-backdrop="static">
			<div class="modal-dialog modal-lg">
			    <div class="modal-content">

	            <div class="modal-header">
	                <h4 class="modal-title">Message Details</h4>
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	            </div>


			        <div class="modal-body">
			            <div ng-show="students.length > 0">
			            	<form ng-submit="postMessage(msgForm.$valid)" name="msgForm" novalidate>

								<div class="row">
									<div class="col-md-4 form-group">
										<label>Communication Type </label><br>
										<label>
											<input type="checkbox" ng-model="formData.send_type[1]"> &nbsp;SMS
										</label>
										&nbsp;&nbsp;&nbsp;
										<label>
											<input type="checkbox" ng-model="formData.send_type[2]"> &nbsp;Email
										</label>

									</div>
								</div>

								<div class="row" ng-if="formData.send_type[1]">
									<div class="col-md-6 form-group">
										<label>SMS Type</label><br>
										<label>
											<input type="radio" ng-required="formData.send_type == 1" ng-model="formData.sms_type" ng-change="popTemplateList()" value="1"> &nbsp;Promotional
										</label>
										&nbsp;&nbsp;
										<label>
											<input type="radio" ng-change="popTemplateList()" ng-required="formData.send_type == 1" ng-model="formData.sms_type" value="2"> &nbsp;Transactional
										</label>
									</div>
								</div>

								<div ng-if="formData.send_type[1]">
                                    <!-- <label>SMS Content</label>
                                    <div class="form-group">
                                        <textarea class="form-control" ng-model="formData.sms_content"></textarea>
                                    </div> -->

                                    <label>SMS Template <span class="error">*</span></label>
                                    <select ng-model="formData.template_id"  class="form-control" required>
                                    	<option value="">Select</option>
                                    	<option value="@{{template.id}}" ng-repeat="template in template_lists">@{{template.template}}</option>
                                    </select>
                                    
                                </div>

                                <div class="row" ng-if="formData.send_type[1]" style="margin-top:20px">
									<div class="col-md-4 form-group">
										<label>Variable 1 </label><br>
										<input type="text" class="form-control" ng-model="formData.variable1">
									</div>
									<div class="col-md-4 form-group">
										<label>Variable 2 </label><br>
										<input type="text" class="form-control" ng-model="formData.variable2">
									</div>
									<div class="col-md-4 form-group">
										<label>Variable 3 </label><br>
										<input type="text" class="form-control" ng-model="formData.variable3">
									</div>
								</div>

								<div ng-if="formData.send_type[2]">
									<label> Subject</label>
									<div class="form-group">
										<input type="text" ng-model="formData.subject" class="form-control" required>
									</div>
								</div>

								<div ng-if="formData.send_type[2]">
                                    <label>Email Content</label>
                                    <div class="form-group">
                                        <trix-editor angular-trix class="trix-content"  ng-model="formData.content" style="height: 200px; overflow: auto;"></trix-editor>
                                    </div>
                                </div>


								<div class="row">
									<div class="col-md-4">
										<label>Demo Check</label><br>
										<input type="checkbox" ng-model="formData.demo_check" value="1">
									</div>
									<div class="col-md-4 form-group" ng-show="formData.demo_check && formData.send_type[1]">
										<label>Test Number</label>
										<input type="text" ng-model="formData.demo_mobile" class="form-control">
									</div>
									<div class="col-md-4 form-group" ng-show="formData.demo_check && formData.send_type[2]">
										<label>Test Email</label>
										<input type="text" ng-model="formData.demo_email" class="form-control">
									</div>

								</div>

								<div style="margin-top: 5px">
									<button class="btn btn-primary" ladda="processing">Send</button>
								</div>
			            	</form>
						</div>
			            
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/students/communicationCtrl.js?v='.env('JS_VERSION')) }}" ></script>
@endsection
