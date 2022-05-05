<div class="form-group">
    @if($label)
    <label>{{$label}} @if($required) <span class="text-danger">*</span> @endif</label>
    @endif
    @php
        $valiadtion = "";
        
    @endphp
    
    
        <input type="{{$type}}" class="form-control" ng-model="{{$name}}" @if($required) required @endif />
</div>