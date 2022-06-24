@extends('layout')

@section('content')

<div class="ng-cloak" ng-controller="SettingsController" ng-init="init()">

	<div class="page-header row">
		<div class="col-6">
			<h3>Settings</h3>
		</div>
		<div class="col-6">
			
		</div>
	</div>

	<div class="portlet">
	  	<div class="portlet-head">
	    	<ul class="menu">
	      		<li class="@{{switchContent == 'general'?'active':''}}">
	        		<a href="" ng-click="switchContent('general')">General</a>
	      		</li>

	      		<li class="@{{switchContent == 'subscriptions'?'active':''}}">
	        		<a href="" ng-click="switchContent('subscriptions')">Performance</a>
	      		</li>
	      		
	      		<li class="@{{switchContent == 'subscriptions'?'active':''}}">
	        		<a href="" ng-click="switchContent('subscriptions')">Emails</a>
	      		</li>

	      		<li class="@{{switchContent == 'subscriptions'?'active':''}}">
	        		<a href="" ng-click="switchContent('subscriptions')">Payment Gateway</a>
	      		</li>

	      		<li class="@{{switchContent == 'subscriptions'?'active':''}}">
	        		<a href="" ng-click="switchContent('subscriptions')">Web Pages</a>
	      		</li>

	    	</ul>
	  	</div>
	  

	  	<div class="portlet-body">
	    	@include("manage.settings.items")
	  	</div>
	</div>

	<div class="modal fade" id="editor_modal" data-backdrop="static" data-keyboard="false" role="dialog"  style="overflow: scroll;">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title">Editor</h4>
                	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
	            </div>
	            <div class="modal-body">
	            	<div ng-if="show_editor" class="ckeditor-content form-group">
						<textarea ckeditor class="form-control" ng-model="editor_data" id="editor_data" style="height: 300px; border: 1px solid #000;"></textarea>
					</div>
	            </div>
	            <div class="modal-footer">
	            	<button type="button" class="btn dark" ng-click="closeEditorModal()">Cancel</button>
	            	<button type="button" class="btn blue" ng-click="saveEditorModal()">Enter</button>
	            </div>
	        </div>
	    </div>
	</div>

</div>
@endsection


@section('footer_scripts')
	<script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/settings/settings_controller.js?v='.env('JS_VERSION'))}}" ></script>
@endsection
