<!DOCTYPE html>
<html>
<head>
    <title>Academy</title>
    <meta name=viewport content="initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{url('assets/plugins/simple-ine/css/simple-line-icons.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{url('/assets/css/custom.css')}}">

</head>

<body ng-app="app">
    @yield('content')
    <script type="text/javascript">
        var base_url = "{{url('/')}}";
    </script>
    @yield('footer_scripts')
</body>

</html>