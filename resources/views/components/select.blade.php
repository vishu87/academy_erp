@props([
    'link'
])
<div class="form-group">
    <div class="row">
        <div class="col">
            
        </div>
        @isset($link)
        <div class="col text-right">
            {{ $link ?? '' }}
        </div>
        @endif
    </div>
    <select class="form-control" ng-model="{{$name}}" @if($required) required @endif>
        {{$slot}}
    </select>
</div>