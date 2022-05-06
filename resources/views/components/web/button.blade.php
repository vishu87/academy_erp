<button type="{{$type}}" class="btn black" @if($loading) ng-disabled="{{$loading}}" @endif @if($ngClick) ng-click="{{$ngClick}}" @endif>
    {{$slot}}
    @if($loading)<span ng-if="{{$loading}}" class="spinner-border spinner-border-sm"></span>@endif
</button>