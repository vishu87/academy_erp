<div>
	<div ng-repeat="item in items">
		<div class="table-div full" style="border-bottom: 1px solid #EEE; padding-bottom: 10px; margin-bottom: 10px">
			<div style="width: 200px">
				<b>@{{ item.parameter }}</b>
			</div>
			<div>
				
				<div ng-if="item.type == 'text' " class="form-group" style="margin-bottom: 0; width: 500px;">
					<input type="text" ng-model="item.value" class="form-control" />
				</div>

				<div ng-if="item.type == 'textarea' " class="form-group" style="margin-bottom: 0; width: 500px">
					<textarea ng-model="item.value" class="form-control" ></textarea>
				</div>

				<div ng-if="item.type == 'image' " class="form-group" style="margin-bottom: 0; width: 500px">
					<div ng-if="item.value">
						<img ng-src="@{{ item.value }}" style="max-height: 200px; width: auto;" />
						<button type="button" class="btn btn-danger btn-xs" ng-click="removeImage(item)">Remove</button>
					</div>

					<div ng-if="!item.value">
						<button type="button" class="btn btn-light" ngf-select="uploadImage($file,item)" ngf-max-size="5MB" ng-disabled="item.uploading">Upload <span ng-if="item.uploading" class="spinner-border spinner-border-sm"></span> <span ng-if="item.uploading">@{{item.progress}}</span> </button>
					</div>

				</div>

				<div ng-if="item.type == 'editor' " class="form-group" style="margin-bottom: 0; width: 500px">
					<div ng-bind-html="item.value" ng-click="openEditor(item)" class="content-div"></div>
				</div>

				<div ng-if="item.type == 'toggle' " class="form-group" style="margin-bottom: 0; width: 500px">
					<label class="switch">
						<input type="checkbox" ng-checked="item.value == 1" ng-click=" item.value = item.value == 1 ? 0 : 1 ">
						<span class="slider round"></span>
					</label>
				</div>

				<small>@{{ item.remarks }}</small>
			</div>
		</div>
	</div>
	<div class="text-right">
		<button type="button" class="btn btn-primary" ng-click="saveSettings()" ng-disabled="processing">Save <span ng-if="processing" class="spinner-border spinner-border-sm"></span></button>
	</div>
</div>