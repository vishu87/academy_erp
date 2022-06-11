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
	    <div ng-if="center.images.length > 0" class="center-images">
	    	<div ng-repeat="image in center.images">
	    		<a href="@{{image.image}}" target="_blank">
	    			<img src="@{{image.image_thumb ? image.image_thumb : image.image}}">
	    		</a>
	    		<a class="btn btn-sm btn-danger btn-remove" ladda="image.processing" ng-click="removeImage(image,$index)"><i class="icons icon-close" style="color: #FFF;"></i></a>
	    	</div>
	    </div>
		<div style="margin-top: 20px">
			<button class="button btn btn-primary" ngf-select="uploadCenterImage($file,center)" ngf-max-size="5MB" ng-disabled="imageProcessing" data-style="expand-right" >Upload Image <span ng-show="imageProcessing" class="spinner-border spinner-border-sm"></span></button>
		</div>
	</div>

</div>