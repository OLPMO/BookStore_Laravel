@extends('master')
@section('title','结算')

@section('content')
<link rel='stylesheet' href='http://{{$WEB_IP}}/css/pay.css'/>
<div class='container pagecontainer'>
@if(0==$status->statusId)
@for($i = 0; $i<count($payOrderItems); $i++)
	<div class="media">
		<div class="media-left">
			<a href="#">
				<img class="media-object" src="http://{{$WEB_IP}}/images/{{$payOrderItems[$i]->preview}}" alt="...">
			</a>
		</div>
		<div class="media-body">
			<h4 class="media-heading">{{$payOrderItems[$i]->name}}</h4>
			<p>单价：{{$payOrderItems[$i]->price}}</p>
			<p>数量：{{$cmdyNum[$payOrderItems[$i]->id]}}</p>
			<div class='btn-group'>
				<button role='delete' mark='{{$payOrderItems[$i]->id}}' type="button" class="btn btn-success " >
				    删除
				</button>
			</div>
		</div>
	</div>
@endfor
<hr role='tpSeparator'/>
<div role='totalPrice' class='col-xs-offset-9 col-sm-offset-10 col-md-offset-10 col-lg-offset-10'>合计：￥{{$totalPrice}}</div>
<hr/>
@endif
</div>
<div class='tailcontainer container'>
<div class='btn-group btn-group-justified ' role='btnMethod'>
	<div class='btn-group'>
		<button type='button' class=' btn btn-primary dropdown-toggle' data-toggle="dropdown"><span>支付宝支付</span>
		</button>
		<ul class='dropdown-menu col-xs-12' >
			<li><a>支付宝支付</a></li>
			<li class='divider'></li>
			<li><a>微信支付</a></li>
		</ul>
	</div>

</div>
<hr class='btnSeparator'/>
<div class="btn-group btn-group-justified " role="btnPay" aria-label="...">
  <div class="btn-group " role="group">
    <button type="button" class=" btn btn-success" role='pay'>付款&nbsp;&nbsp;<span class='badge'>￥{{$totalPrice}}</span></button>
  </div>
</div>
</div>
@endsection

@section('myJs')
<script>
	function delAjax(){
		$.ajax({
			type: "POST",
			url: "http://{{$WEB_IP}}/order/pay/delete/{{$orderId}}",
			dataType:'json',
			data:{
			@if(0==$status->statusId)
				//其实标记就是产品id，方便查找数据
				mark:$(this).attr('mark'),
			@endif
			'_token':'{{csrf_token()}}'
			},
			/*
			 *浏览器删除完相应的数据库信息后，便可以把这本书的显示信息删除.
			 *否则，删除失败
			 *其实用不着拼接字符串这么麻烦（这里仅是因为购物车用了这样的方法，所以才这样做）
			 *直接reload接好了，因为数据都是数据库中读取的
			 */
			success:function(msg){
				window.location.reload();
			/*	var orderHtml='';
				if(0==msg.statusId){
					for(var i=0;i<msg.result.pdtInfo.length;i++){
						orderHtml=orderHtml+"<div class='media'>"+
								 "<div class='media-left'>"+
								 "<a href='#'>"+
								"<img class='media-object' src='http://{{$WEB_IP}}/images/"+msg.result.pdtInfo[i].preview+"' alt='...'/>"+
								'</a>'+
								'</div>'+
								"<div class='media-body'>"+
								"<h4 class='media-heading'>"+msg.result.pdtInfo[i].name+"</h4>"+
								"<p>单价："+msg.result.pdtInfo[i].price+"</p>"+
								"<p>数量："+msg.result.orderItems[i].product_num+"</p>"+
								"<div class='btn-group'>"+
								"<button onClick='delAjax()' role='delete' type='button' class='btn btn-success'>"+
								" 删除"+
								"</button>"+
								"</div>"+
								"</div>"+
								"</div>";
					}
					$("button[role='pay'] .badge").html(msg.statusMsg);
					$("div[role='totalPrice']").html('合计：￥'+msg.statusMsg);
				}else{
					$("button[role='pay'] .badge").html('0.00');
					$("div[role='totalPrice']").html('合计：￥0.00');
				}
				if($('div').hasClass('media')){
					$('.media').empty();
					$('div').remove('.media');
				}
				$("hr[role='tpSeparator']").before(orderHtml);	*/
			}
		});
	
	}
	$("div[role='btnMethod'] li a").click(function(){
		$("div[role='btnMethod'] button span").html($(this).html());
		$('.btnSeparator').css('display','block');
		$("div[role='btnPay']").css('display','block');
	});
	$("button[role='delete']").click(delAjax);
</script>
@endsection
