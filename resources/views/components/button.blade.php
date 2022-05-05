<button type="{{$type}}" class="btn btn-block btn-primary" @if($spin) ng-disabled="{{$spin}}" @endif>
    {{$slot}}
    @if($spin)<span ng-if="{{$spin}}" class="spinner-border spinner-border-sm"></span>@endif
</button>