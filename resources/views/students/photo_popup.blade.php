<div id="playerPhoto" class="modal fade in small-modal modal-overflow">
    <div class="modal-dialog" role="document"> 
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Upload Picture</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <div class="pic-changer" >
          <div class="crop-area">
            <div class="cropArea">
                <ui-cropper image="myImage" area-type="square" result-image="myCroppedImage" live-view="blockingObject" aspect-ratio="1" @if(!isset($no_aspect)) @endif></ui-cropper>
            </div>
            <div class="select-file" ng-show="myImage == ''">
              <label class="fileContainer">
                  Select Photograph
                  <input type="file" id="fileInput" ngf-pattern="'image/jpeg,jpg,png" accept=".jpeg,.jpg,.JPG,.JPEG,.png,>PNG">
              </label>
            </div>
          </div>

          <div class="row" style="margin-top: 20px; ">
            <div class="col-6">
              <div ng-show="myImage != ''" >
                <button type="button" ng-click="discard()" class="btn grey small" ng-disabled="uploading">Discard</button>
                </div>
            </div>
            <div class="col-6 text-right">
              <button class="btn btn-primary" type="button" ng-click="selectCroppedProfilePic()" ladda="uploading">Select</button>
            </div>
          </div>
            
        </div>
      </div>
    </div>
    </div>
</div>

<div id="ChangeplayerPhoto" class="modal fade in small-modal modal-overflow">
    <div class="modal-dialog" role="document"> 
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Change Picture</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <div class="pic-changer" >
          <div class="crop-area">
            <div class="cropArea">
                <ui-cropper image="myImage" area-type="square" result-image="myCroppedImage" live-view="blockingObject" @if(!isset($no_aspect)) @endif></ui-cropper>
            </div>
            <div class="select-file" ng-show="myImage == ''">
              <label class="fileContainer">
                  Select Photograph
                  <input type="file" id="fileInput" ngf-pattern="'image/jpeg,jpg" accept=".jpeg,.jpg,.JPG,.JPEG,">
              </label>
            </div>
          </div>

          <div class="row" style="margin-top: 20px; ">
            <div class="col-6">
              <div ng-show="myImage != ''" >
                <button type="button" ng-click="discard()" class="btn grey small" ng-disabled="uploading">Discard</button>
                </div>
            </div>
            <div class="col-6 text-right">
              <button class="btn btn-primary" type="button" ng-click="selectCroppedChangePic()" ladda="uploading">Select</button>
            </div>
          </div>
            
        </div>
      </div>
    </div>
    </div>
</div>

