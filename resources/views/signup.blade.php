@extends('master')
@section('title','注册')

@section('content')
<link href='css/signup.css' rel='stylesheet' type="text/css"/>
<form method='post' action='http://localhost/signup/signinfo' class="form-horizontal">
<input type='hidden' name='_token' value="{{ csrf_token() }}"/>
	<div class="form-group">
		<label class="col-sm-2 control-label">邮箱：</label>
		<div class="col-sm-10 col-md-8">
			<input name='uEmail' type="email" class="form-control" placeholder="Email">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">用户名 ：</label>
		<div class="col-sm-10 col-md-8">
			<input name='uName' type="text" class="form-control" placeholder="用户名">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">密码：</label>
		<div class="col-sm-10 col-md-8">
			<input type="password" name='uPass' class='form-control' placeholder="不少于6位字符">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">确认密码：</label>
		<div class="col-sm-10 col-md-8">
			<input type="password" class='form-control' name='uConfirmPass'  placeholder="不少于6位字符">
		</div>
	</div>	
	<div class="form-group">
		<button type="submit" name='doSubmit' class="col-xs-8 col-xs-offset-2 col-sm-offset-9 col-sm-2 col-md-1 col-md-offset-8 btn btn-default">注册</button>
	</div>
</form>
@endsection

@section('myJs')
	<script>
	</script>
@endsection
