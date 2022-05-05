<div class="portlet">
	<div class="portlet-head">
		<div class="row">
			<div class="col-md-6">
				<ul class="menu">
					<li class="active">
						<a href="#">Center Images</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">
	    <div class="row" ng-if="center.images.length > 0" style="margin-top: 20px;">
	    	<div class="col-md-2" ng-repeat="image in center.images" style="position: relative;">
	    		<a href="@{{image.image}}" target="_blank">
	    			<img src="{{url('/')}}/@{{image.image_thumb ? image.image_thumb : image.image}}" style="width: 150px; height:auto">
	    		</a>
	    		<a class="btn btn-sm btn-danger" ladda="image.processing" ng-click="removeImage(image,$index)" style="position: absolute;top: 4px;right: 18px"><i class="icons icon-close"></i></a>
	    	</div>
	    </div>
		<div style="margin-top: 10px">
			<button ng-show="!center_image || center_image == ''" class="button btn btn-primary" ngf-select="uploadCenterImage($file,center)" ngf-max-size="5MB" ng-disabled="imageProcessing" data-style="expand-right" >Upload Image <span ng-show="imageProcessing" class="spinner-border spinner-border-sm"></span></button>
		</div>
	</div>

</div>