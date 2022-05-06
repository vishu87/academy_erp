<div class="form-group">
    @if($label)
    <label>{{$label}} @if($required) <span class="text-danger">*</span> @endif</label>
    @endif
    @php
        $validation = "";
        if($type == "mobile") {
            $type = "text"; $validation = "mobile";
        } else if($type == "pin_code") {
            $type = "text"; $validation = "pincode";
        }
    @endphp
    
    @if($type == "textarea")
        <textarea class="form-control" @if($placeholder) placeholder ="{{$placeholder}}" @endif ng-model="{{$name}}" @if($required) required @endif rows="{{ $rows }}"></textarea>
    @else

        <input type="{{$type}}" class="form-control" ng-model="{{$name}}" @if($required) required @endif @if($validation == 'mobile') ng-pattern="/^[0-9]{10}$/" ng-pattern-err-type="PatternMobile" @endif @if($validation == 'pincode') ng-pattern="/^[0-9]{6}$/" ng-pattern-err-type="PatternPin" @endif  />
    @endif
</div>