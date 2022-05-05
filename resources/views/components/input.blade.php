<div class="form-group">
    @if($label)
    <label>{{$label}} @if($required) <span class="text-danger">*</span> @endif</label>
    @endif
    @php
        $valiadtion = "";
        
    @endphp
    
    @if($type == "textarea")
        <textarea class="form-control" @if($placeholder) placeholder ="{{$placeholder}}" @endif ng-model="{{$name}}" @if($required) required @endif rows="{{ $rows }}"></textarea>
    @else
        <input type="{{$type}}" class="form-control" ng-model="{{$name}}" @if($required) required @endif />
    @endif
</div>