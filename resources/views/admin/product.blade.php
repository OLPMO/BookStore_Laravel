@extends('admin.master')
@section('title','产品管理')
@section('content')
<link href="http://{{$WEB_IP}}/css/admin/product.css" rel="stylesheet">
		<div id='mymainpage' class='mainpage main'>
			<ol class="breadcrumb">
				<button id='addCateButton'  type="button" class="btn btn-info" data-toggle="modal" data-target="#addCateModal">添加产品</button>
				<span class='col-sm-offset-10'>共&nbsp;{{$pdtNum}}&nbsp;件产品</span>
			</ol>

			<div id='FirTableRow' class='row'>
				<div class='col-sm-11'>
				<div class=" panel panel-default">
					<div class="panel-heading">产品管理</div>
					<div class="panel-body">
						<table class='table table-bordered table-hover'>
						<tr>
							<td>产品名称</td>
							<td>产品类别</td>
							<td>价格</td>
							<td >产品预览</td>
							<td role='pdtSummary'>产品简介</td>
							<td role='pdtManage'>管理</td>
						</tr>
					@foreach($products as $product)
						<tr>
							<td>{{$product->name}}</td>
							<td>{{$pdtInfo[$product->id]}}</td>
							<td>{{$product->price}}</td>
							<td><img src='http://{{$WEB_IP}}/images/{{$product->preview}}'/></td>
							<td role='pdtSummary'>{!!$product->summary!!}</td>
							<td role='pdtManage'>
								<span name='{{$product->name}}' mark='{{$product->id}}' class='glyphicon glyphicon-edit'> </span>
								<span name='{{$product->name}}' mark='{{$product->id}}' class='glyphicon glyphicon-list-alt'> </span>
								<span name='{{$product->name}}' mark='{{$product->id}}' class='glyphicon glyphicon-remove'></span>
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
	$("td[role='pdtManage'] .glyphicon-edit").click(function(){
		window.location.href='http://{{$WEB_IP}}/admin/product/edit/'+$(this).attr('mark');
	});
	$("td[role='pdtManage'] .glyphicon-remove").click(function(){
		if(confirm('你是否要删除“'+$(this).attr('name')+'“这一商品？')){
			$.post(
				'http://{{$WEB_IP}}/admin/product/delete',
				{'product_id':$(this).attr('mark'),'_token':"{{csrf_token()}}"},
				function(msg){
					if(0==msg.statusId){
						alert(msg.statusMsg);
						window.location.reload();
					}else{
						alert(msg.statusMsg);
					}
				},
				'json'
			);
		}
	});

</script>
@endsection

