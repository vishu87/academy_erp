<div class="form-group">
    <label>{{$label}}</label>
    <div style="background: #EEE; border-radius: 5px; border: 1px dashed #000; padding: 25px; text-align: center;" ngf-select="uploadDocument($file)" ngf-drop="uploadDocument($file)" ng-hide="{{$name}}">
        Drag &amp; Drop
        or
        Browse File
    </div>
    <div ng-show="{{$name}}">
        <a href="{{<?php echo $name ?>}}" target="_blank" class="btn btn-primary">View</a>
        <button type="button" class="btn btn-danger" ng-click="{{$name}} = '' ">Remove</button>
    </div>
</div>