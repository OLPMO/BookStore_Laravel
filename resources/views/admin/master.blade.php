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
	<nav class='navbar navbar-default navbar-static-top'>
		<div class='navbar-header'>
			<div class='navbar-brand'>
				<a href="http://{{$WEB_IP}}/admin">为学书店后台管理系统</a>				
			</div>
			<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navcont'>
				<span class="sr-only">Toggle navigation</span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
			</button>
		</div>
		<div class='navbar-default collapse navbar-collapse' id='navcont'>
			<ul class='nav navbar-nav navbar-right'>
				<li><a href="#"><span class="badge" >42</span></a></li>
				<li class='closeicon'><A><div class='glyphicon glyphicon-off'></div></A></li>
			</ul>
			<ul id='mysidebar' class='nav navbar-default'>
				<li  data-toggle='collapse' data-target='#bloglistcont'><a><span class='glyphicon glyphicon-briefcase'></span>&nbsp;&nbsp;产品管理</a>
					<ul class='nav collapse list-unstyled '  id='bloglistcont'>
						<li><a href="http://{{$WEB_IP}}/admin/category">分类管理</a></li>
						<li><a href="http://{{$WEB_IP}}/admin/product">产品管理</a></li>
					</ul>
				</li>
				<li  ><a href='http://{{$WEB_IP}}/admin/member'><span class='glyphicon glyphicon-user'></span>&nbsp;&nbsp;会员管理</a></li>
			</ul>
				
		</div>
		</nav>
	@yield('content')
</body>
@yield('myJs')
