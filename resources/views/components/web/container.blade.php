<div class="main ng-cloak" style="background: {{ $background  }}; min-height: 100%;" @if($controller) ng-controller="{{$controller}}"  @endif @if($init) ng-init="{{$init}}" @endif>
    <div class="container" style="padding-bottom: 100px; position: relative;">
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

        <div class="web-footer">
            {!! $footer !!}
            <div>
                <ul>
                    <li>
                        <a href="{{url('pages/terms-conditions')}}" target="_blank">Terms & Conditions</a>
                    </li>
                    <li>
                        <a href="{{url('pages/privacy-policy')}}" target="_blank">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="{{url('pages/refund-policy')}}" target="_blank">Refund Policy</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>