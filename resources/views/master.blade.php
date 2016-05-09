<!Doctype html>
<head>
	<meta charset='utf-8'/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="http://{{$WEB_IP}}/BootCSS/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="http://{{$WEB_IP}}/jQuery/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="http://{{$WEB_IP}}/BootJS/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://{{$WEB_IP}}/jQueryForm/jquery.form.js"></script>
	<title>@yield('title')</title>
</head>
<body>
	@yield('content')
</body>
@yield('myJs')
