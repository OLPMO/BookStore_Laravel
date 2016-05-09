@extends('admin.master')
@section('title','会员管理')
@section('content')
<link href="http://{{$WEB_IP}}/css/admin/product.css" rel="stylesheet">
		<div id='mymainpage' class='mainpage main'>
			<ol class="breadcrumb">
				<span class='col-sm-offset-10'>共&nbsp;{{$members->count()}}&nbsp;件产品</span>
			</ol>

			<div id='FirTableRow' class='row'>
				<div class='col-sm-11'>
				<div class=" panel panel-default">
					<div class="panel-heading">产品管理</div>
					<div class="panel-body">
						<table class='table table-bordered table-hover'>
						<tr>
							<td>用户id</td>
							<td>用户名</td>
							<td>邮箱</td>
							<td>电话</td>
							<td>是否激活</td>
							<td>创建时间</td>
							<td>是否被封号</td>
							<td>管理</td>
						</tr>
					@foreach($members as $member)
						<tr>
							<td>{{$member->id}}</td>
							<td>{{$member->nickname}}</td>
							<td>{{$member->email}}</td>
							<td>{{$member->phone}}</td>
							<td>{{$member->active}}</td>
							<td>{{$member->created_at}}</td>
							<td>{{$member->disabled}}</td>
							<td role='mbManage'>
								<span name='{{$member->name}}' mark='{{$member->id}}' class='glyphicon glyphicon-folder-close'></span>
								<span name='{{$member->name}}' mark='{{$member->id}}' class='glyphicon glyphicon-folder-open'></span>
							</td>
						</tr>
					@endforeach
						</table>
					</div>
				</div>
				</div>
			</div>
		</div>
@endsection

@section('myJs')
<script>
	$('.glyphicon-folder-close').click(function(){
		$.post(
			'http://{{$WEB_IP}}/admin/member/disable',
			{'member_id':$(this).attr('mark'),'_token':"{{csrf_token()}}"},
			function(msg){
				alert(msg.statusMsg);
				if(0==msg.statusId){
					window.location.reload();
				}
			},
			'json'
		);

	});
	$('.glyphicon-folder-open').click(function(){
		$.post(
			'http://{{$WEB_IP}}/admin/member/able',
			{'member_id':$(this).attr('mark'),'_token':"{{csrf_token()}}"},
			function(msg){
				alert(msg.statusMsg);
				if(0==msg.statusId){
					window.location.reload();
				}
			},
			'json'
		);

	});
</script>
@endsection

