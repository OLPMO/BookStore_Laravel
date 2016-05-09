@extends('admin.master')
@section('title','后台')
@section('content')
<link href="http://{{$WEB_IP}}/css/BackStage.css" rel="stylesheet">
		<div id='mymainpage' class='mainpage main'>
			<ol class="breadcrumb">
				<button id='addCateButton'  type="button" class="btn btn-info" data-toggle="modal" data-target="#addCateModal">添加类别</button>
				<div id='addCateModal' class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="gridSystemModalLabel">类别管理</h4>
							</div>
							<div class="modal-body">
								<div class="container-fluid">
									<form class="form-horizontal">
										<div class="form-group">
											<label class="col-sm-2 control-label">类别名</label>
											<div class="col-sm-10">
												<input id='addCateInput' type="text" class="form-control"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">父类别</label>
											<div class="col-sm-10 dropdown">
												<input id='addCatePareInput' mark='0' value='Null' readonly='true' type="text" class="form-control  dropdown-toggle" id="pareCate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"/>
												<ul class="dropdown-menu col-sm-11" aria-labelledby="pareCate">
												<li mark='0' value='Null'><a>Null</a></li>
												@for($i=0;$i<count($firsCategory);$i++)
													<li mark="{{$firsCategory[$i]['cateId']}}" value="{{$firsCategory[$i]['cateName']}}"><a>{{$firsCategory[$i]['cateName']}}</a></li>
												@endfor
												</ul>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="modal-footer">
								<button role='close' type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
								<button role='ok' type="button" class="btn btn-primary">确认</button>
								<button role='savechanges' type='button' class='btn btn-primary'>保存修改</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<span class='col-sm-offset-10'>共&nbsp;{{$recoNum}}&nbsp; 条记录</span>
			</ol>

			<div id='FirTableRow' class='row'>
				<div class='col-sm-11'>
				<div class=" panel panel-default">
					<div class="panel-heading">类别管理</div>
					<div class="panel-body">
						<table class='table table-bordered table-hover'>
						<tr>
							<td>id</td>
							<td>类别名称</td>
							<td>父类别</td>
							<td>产品数量</td>
							<td>管理</td>
						</tr>
					@foreach($categories as $category)
						<tr>
							<td>{{$category->id}}</td>
							<td>{{$category->name}}</td>
							<td>
								@if(!empty($category->parent_id))
									{{$cateInfo[$category->id]['pareName']}}
								@endif
							</td>
							<td>{{$cateInfo[$category->id]['pdtNum']}}</td>
							<td>
								<span name='{{$category->name}}' mark='{{$category->id}}' class='glyphicon glyphicon-edit'> </span>
								<span name='{{$category->name}}' mark='{{$category->id}}' class='glyphicon glyphicon-remove'></span>
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
	var editId=0;
	$('.modal-body .dropdown li').click(function(){
		$('.modal-body .dropdown input').val($(this).attr('value'));
		$('.modal-body .dropdown input').attr('mark',$(this).attr('mark'));
		
	});
	$("#addCateButton").mouseover(function(){
		$("button[role='savechanges']").css('display','none');
		$("button[role='ok']").css('display','inline-block');
		//清空修改类别时留下的信息
		$('#addCateInput').val('');
        $(".modal .dropdown li[mark='0']").trigger('click');
	});
	$("button[role='ok']").click(function(){
		$.post("http://{{$WEB_IP}}/admin/addcate", 
			{"name":$('#addCateInput').val(),'parentId':$('#addCatePareInput').attr('mark'),'_token':"{{csrf_token()}}" },
			function(msg){
				if(0==msg.statusId){
					window.location.href='http://{{$WEB_IP}}/admin/category';
				}else{
					confirm(msg.statusMsg);
				}
			},
			"json");
		$("button[role='close']").trigger('click');

	});
	$("button[role='savechanges']").click(function(){
		$.post("http://{{$WEB_IP}}/admin/editcate", 
			{'id':editId,"name":$('#addCateInput').val(),'parentId':$('#addCatePareInput').attr('mark'),'_token':"{{csrf_token()}}" },
			function(msg){
				if(0==msg.statusId){
					window.location.href='http://{{$WEB_IP}}/admin/category';
				}else{
					confirm('修改失败');
				}
			},
			"json");
		$("button[role='close']").trigger('click');
	});
	$('.glyphicon-edit').click(function(){
		editId=$(this).attr('mark');
		$.post(
			"http://{{$WEB_IP}}/admin/editcate",
			{'id':$(this).attr('mark'),'_token':"{{csrf_token()}}"},
			function(msg){
				if(0==msg.statusId){
					//设置模态框中相应的信息，然后触发它
					$('#addCateInput').val(msg.statusMsg.name);
					$(".modal .dropdown li[mark='"+msg.statusMsg.parent_id+"']").trigger('click');						
					$("button[role='savechanges']").css('display','inline-block');
					$("button[role='ok']").css('display','none');
					$('#addCateButton').trigger('click');
				}else{
					window.location.reload();
				}
			},
			'json'
		);
	});
	$('.glyphicon-remove').click(function(){
		confirm('你是否要删除”'+$(this).attr('name')+'“这一分类？ ');
		$.post(
			"http://{{$WEB_IP}}/admin/delcate",
			{'id':$(this).attr('mark'),'_token':"{{csrf_token()}}"},
			function(msg){
				if(0==msg.statusId){
					window.location.reload();
				}else{
					confirm(msg.statusMsg);
				}
			},
			'json'
		);
	});
</script>
@endsection

