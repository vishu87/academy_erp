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
			<div class="col-md-6 text-right">
				<button ng-show="!center_image || center_image == ''" class="button btn btn-success" ngf-select="uploadCenterImage($file,center)" ngf-max-size="5MB" ladda="uploading" data-style="expand-right" >Upload Image</button>
			</div>
		</div>
	</div>

	<div class="portlet-body ng-cloak">

	    <div class="row" ng-if="center.images.length > 0" style="margin-top: 20px;">
	    	<div class="col-md-2" ng-repeat="image in center.images" style="position: relative;">
	    		<a href="@{{image.image}}" target="_blank">
	    			<img src="@{{image.image_thumb ? image.image_thumb : image.image}}" style="width: 150px; height:auto">
	    		</a>
	    		<a class="btn btn-sm btn-danger" ladda="image.processing" ng-click="removeImage(image,$index)" style="position: absolute;top: 4px;right: 18px">X</a>
	    	</div>
	    </div>

	</div>

</div>