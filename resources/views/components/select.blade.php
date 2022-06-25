@props([
    'link'
])
<div class="form-group">
    <div class="row">
        <div class="col">
            <label>{{$label}} @if($required)<span class="text-danger">*</span> @endif</label>
            @isset($link)
            <span style="font-size: 11px;">
                {{ $link ?? '' }}
            </span>
            @endif
        </div>
    </div>
    <select class="form-control" ng-model="{{$name}}" @if($required) required @endif @if($ngChange) ng-change="{{$ngChange}}" @endif>
        {{$slot}}
    </select>
</div>