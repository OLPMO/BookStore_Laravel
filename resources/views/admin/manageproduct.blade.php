@extends('master')
@section('title','产品管理')
@section('content')
<link href="http://{{$WEB_IP}}/css/admin/product.css" rel="stylesheet">
<link href="http://{{$WEB_IP}}/css/admin/manageproduct.css" rel="stylesheet">
		<div id='mymainpage' class='mainpage main'>
			<form name='pdtInfo' method='post' ENCTYPE="multipart/form-data">
			{{csrf_field()}}
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">产品名称</label>
					<div class="col-sm-9">
						<input name='pdtName' type="text" class="form-control" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">产品类别</label>
					<div class="col-sm-3 dropdown">
						<input name='pdtCate' readonly='true' mark='0' value='Null' type="text" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<ul class="dropdown-menu col-sm-11" >
						@for($i=0;$i<count($cateInfo);$i++)
							<li name="{{$cateInfo[$i]['name']}}" mark="{{$cateInfo[$i]['id']}}"><a >{{$cateInfo[$i]['name']}}</a></li>
						@endfor
						</ul>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">价格</label>
					<div class="col-sm-3">
						<div class="input-group">
							<span class="input-group-addon">￥</span>
							<input name='pdtPrice' type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class='col-sm-2 control-label'>预览图</label>
					<div class='row'>
					<div  class='col-sm-4' name='prvDiv'>
						@if(isset($product->preview))
							<img src='http://{{$WEB_IP}}/images/{{$product->preview}}'/>
						@else
							<img src=''/>		
						@endif
					<input type="file" name='prvImage' >
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">产品简介</label>
					<div class="col-sm-10">
						<textarea  id="editorSummary"  name="pdtSummary">
						@if(isset($product->summary))
							{!! $product->summary!!}
						@endif
						</textarea>
					</div>
				</div>
				<div role='crsDiv' class="form-group">
					<label class='col-sm-2 control-label'>轮播图</label>
					<!--cre即carousel（boostrap轮播图）的缩写-->
					<div class="row">
					@if(isset($arrPdtImages))
						@for($i=1;$i<count($arrPdtImages)+1;$i++)
						<div class="col-sm-3">
							<a>
								<img src="http://{{$WEB_IP}}/images/{{$arrPdtImages[$i]}}" alt="">
							</a>
							<input type="file" role='crsImage' name="crsImage{{$i}}">
						</div> 
						@endfor
					@else
						@for($i=1;$i<4;$i++)
						<div class="col-sm-3">
							<a>
								<img src="" alt="">
							</a>
							<input type="file" role='crsImage' name="crsImage{{$i}}">
						</div>
						@endfor
					@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">产品介绍</label>
					<div class="col-sm-10">
						<textarea  id="editorContent"  name="pdtContent">
						@if(isset($pdtContent->content))
							{!!$pdtContent->content!!}
						@endif		
						</textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-10 col-sm-2">
						<button role="submit" class="btn btn-default">确认</button>
					</div>
				</div>
			</div>
			</form>
		</div>
@endsection

@section('myJs')
<script type="text/javascript" src="http://{{$WEB_IP}}/myeditor/kindeditor.js"></script>
<script  type="text/javascript" src="http://{{$WEB_IP}}/myeditor/lang/zh_CN.js"></script>
<script>
	/*要做表单验证，及下拉框的动态显示,由于时间关系，及项目重点不在此，故忽略*/
	var contEditor,sumEditor;
	$(".dropdown .dropdown-menu li").click(function(){
		$("input[name='pdtCate']").attr('value',$(this).attr('name'));
		$("input[name='pdtCate']").attr('mark',$(this).attr('mark'));
	});
		
	KindEditor.ready(function(K) {
		sumEditor = K.create('textarea[id="editorSummary"]', {
			uploadJson:'http://{{$WEB_IP}}/mykindeditor/php/upload_json.php',
			fileManagerJson:'http://{{$WEB_IP}}/mykindeditor/php/file_manager_json.php',
			allowFileManager:true,
			resizeType:0
		});	
	});
	KindEditor.ready(function(K) {
		contEditor = K.create('textarea[id="editorContent"]', {
			uploadJson:'http://{{$WEB_IP}}/mykindeditor/php/upload_json.php',
			fileManagerJson:'http://{{$WEB_IP}}/mykindeditor/php/file_manager_json.php',
			allowFileManager:true,
			resizeType:0
		});	
	});
	@if(isset($product))
		$("input[name='pdtName']").attr('value',"{{$product->name}}");
		$("input[name='pdtPrice']").attr('value',"{{$product->price}}");
		$("input[name='pdtCate']").attr('mark',"{{$product->category_id}}");
		@for($i=0;$i<count($cateInfo);$i++)
			@if($product->id==$cateInfo[$i]['id'])
				$("input[name='pdtCate']").attr('value',"{{$cateInfo[$i]['name']}}");
			@endif
		@endfor
	@endif

	$("button[role='submit']").click(function(){
		sumEditor.sync();
		contEditor.sync();
		$("form[name='pdtInfo']").ajaxSubmit({
			url:'http://{{$WEB_IP}}/admin/product/add',
			type:'post',
			dataType:'json',
			success:function(msg){
				if(0==msg.statusId){
					alert(msg.statusMsg);
					window.location.href='http://{{$WEB_IP}}/product';
				}else{
					alert(msg.statusMsg);
				}
			}	
		});
		return false;
	});
</script>
@endsection

