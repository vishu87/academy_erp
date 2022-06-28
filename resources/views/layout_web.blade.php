<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name=viewport content="initial-scale=1">
    
    @if(env('APP_ENV') == "production" || env('APP_ENV') == "online")
        <link rel="stylesheet" type="text/css" href="{{url('assets/dist/css/fonts.css')}}">
        <link rel="stylesheet" type="text/css" href="{{url('assets/dist/css/combined_web.min.css')}}" />
    @else
        <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/simple-ine/css/simple-line-icons.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{url('assets/css/web_custom.css')}}" />
    @endif

</head>
<body ng-app="app">
    @yield('content')
    
    <script type="text/javascript">
        var base_url = "{{url('/')}}";
        var api_key = "";
        var client_id = "{{$client_id}}";
        var currency_format = "INR";
    </script>

    <script src="{{url('assets/plugins/jquery.min.js')}}" type="text/javascript"></script>

    @if(env('APP_ENV') == "production" || env('APP_ENV') == "online")
        <script type="text/javascript" src="{{url('assets/dist/js/bundle_web.min.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/dist/js/combined_web.min.js')}}"></script>
    @else
        <script src="{{url('assets/plugins/popper.js')}}"></script>
        <script src="{{url('assets/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{url('assets/plugins/bootbox.min.js')}}" type="text/javascript"></script>
        <script src="{{url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
        <script type="text/javascript" src="{{url('assets/plugins/echarts.min.js')}}"></script>

        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/angular.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/angular-sanitize.js')}}" ></script>
        
        <!--Begin Angular scripts -->
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/ng-file-upload.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/ng-file-upload-shim.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/jcs-auto-validate.js')}}" ></script>
        <script src="{{url('assets/plugins/admin/scripts/jquery-cropper/croppie.js')}}" type="text/javascript"></script>
        <script src="{{url('assets/plugins/admin/scripts/ui-cropper.js')}}" type="text/javascript"></script>
        <?php $version = "1.0.2"; ?>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/custom.js?v='.$version)}}"></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/app.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/services.js?v='.$version)}}" ></script>

        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/web/reg_controller.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/web/lead_controller.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/web/demo_schedule_controller.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/web/renewal_controller.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/web/signup_controller.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/plugins/admin/scripts/core/web/client_payment_controller.js?v='.$version)}}" ></script>

    @endif

    @yield('footer_scripts')

</body>

</html>