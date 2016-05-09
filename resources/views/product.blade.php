@extends('master')
@section('title',"$product->name")

@section('content')
<link rel='stylesheet' href='http://{{$WEB_IP}}/css/product.css'/>
<div class='container'>
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
		<ol class="carousel-indicators">
			@for($i=0;$i<count($arrPdtImages);$i++)
				<li data-target="#carousel-example-generic" data-slide-to="{{$i}}"></li>	
			@endfor
		</ol>
		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			@for ($i = 0; $i <count($arrPdtImages); $i++)
				<div class="item">
					<img src="http://{{$WEB_IP}}/images/{{$arrPdtImages[$i]}}" alt="...">
				</div>
			@endfor
		</div>
		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	<p>
		{!!$pdtContent!!}
	</p>
	<div class="btn-group btn-group-justified" role="group" aria-label="...">
		<div class="btn-group" role="watchCart">
			<button type="button" class="btn btn-success">
				查看购物车&nbsp;&nbsp;<span class="badge">0</span>
			</button>

	    </div>
		<div class="btn-group" role="buy">
			<button type="button" class="btn btn-success">加入购物车</button>
		</div>
	</div>
</div>
@endsection

@section('myJs')
<script>
	$("li[data-slide-to]").eq(0).addClass('active');
	$(".item").eq(0).addClass('active');
	$.ajax({
		type:'POST',
		url:'http://{{$WEB_IP}}/addcart',
		dataType:'json',
		data:{action:'getsum'},
		success:function(msg){
			$("div[role='pay'] span").html(msg.statusMsg);	
		}
	});
	$("div[role='buy']").click(function(){
		$.ajax({
			type:'POST',
			url:'http://{{$WEB_IP}}/addcart',
			dataType:'json',
			data:{action:'addcart',productId:{{$product->id}}},
			success:function(msg){
				//alert(msg.statusMsg);
				$("div[role='watchCart'] span").html(msg.statusMsg);			
			},
			error:function(msg){
				alert('抱歉！添加购物车失败！请重试');
			}
		});
	});
	$("div[role='watchCart']").click(function(){
		window.location.href='http://{{$WEB_IP}}/cart';
	});
</script>
@endsection
