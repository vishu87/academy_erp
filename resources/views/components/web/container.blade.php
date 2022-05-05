<div class="main ng-cloak" style="background: {{ $background  }}; min-height: 100%" @if($controller) ng-controller="{{$controller}}"  @endif @if($init) ng-init="{{$init}}" @endif>
    <div class="container">
        <div class="" style="text-align: center; padding: 20px;">
            <img src="{{ $logo }}" />
        </div>
        <div class="row">
            <div class="col-md-8" style="margin: 0 auto; background: #FFF">
                <div class="page-content" style="padding: 30px;">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>