<button type="{{$type}}" class="btn black" @if($spin) ng-disabled="{{$spin}}" @endif>
    {{$slot}}
    @if($spin)<span ng-if="{{$spin}}" class="spinner-border spinner-border-sm"></span>@endif
</button>