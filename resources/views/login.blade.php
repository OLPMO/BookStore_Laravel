@extends('master')
@section('title','登录')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href='css/login.css' rel='stylesheet' type="text/css"/>
<div class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2 control-label">用户名</label>
		<div class="col-sm-10 col-md-8">
			<input name='username'  class="form-control" id="inputEmail3" placeholder="用户名">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">密码</label>
		<div class="col-sm-10 col-md-8">
			<input name='password' type="password" class='form-control' id="inputPassword3" placeholder="密码">
		</div>
	</div>
	<div class="form-group">
		<div class="">	
			<button  name='signUp' class="col-xs-12 col-sm-offset-2 col-sm-4 col-md-1 btn btn-default">注册</button>
			<button  name='logIn' class="col-xs-12 col-sm-push-1 col-sm-4  col-md-push-5 col-md-1 btn btn-default">登录</button>
		</div>
	</div>
</div>
@endsection

@section('myJs')
<script>
	$("button[name='logIn']").click(function(){

		$.ajax({
			type: "POST",
			url: "/login/check",
			headers:{
				'X-CSRF-Token': $("meta[name='csrf-token']").prop('content')
			},
			cache: false,
			dataType:'json',
			data: {username:$("input[name='username']").val(),password:$("input[name='password']").val()},
			success: function(msg){
				if(0==msg.statusId){
					window.location.href="http://{{$WEB_IP}}/category";
				}else{
					alert(msg.statusMsg);
				}
			},
		});

	});
</script>
@endsection
