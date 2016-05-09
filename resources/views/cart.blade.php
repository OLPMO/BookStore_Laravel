@extends('master')
@section('title','购物车')

@section('content')

@if(0==$status->statusId)
<link rel='stylesheet' href='http://{{$WEB_IP}}/css/cart.css'/>
<div class='container'>
@for($i = 0; $i<count($cartProducts); $i++)
	<div class="media">
		<div class="media-left">
			<a href="#">
				<img class="media-object" src="http://{{$WEB_IP}}/images/{{$cartProducts[$i]['preview']}}" alt="...">
			</a>
		</div>
		<div class="media-body">
			<h4 class="media-heading">{{$cartProducts[$i]['name']}}</h4>
			<p>单价：{{$cartProducts[$i]['price']}}</p>
			<p>数量：{{$cartMessages[$cartProducts[$i]['id']]}}</p>
			<div class='btn-group'>
				<button role='delete' type="button" class="btn btn-success " >
				    删除
				</button>
			</div>
		</div>
	</div>
@endfor
<div class="btn-group btn-group-justified" role="menu" aria-label="...">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-primary" role='ctnBuy'>继续购物</button>
  </div>
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-primary" role='pay'>结算&nbsp;&nbsp;<span class='badge'>{{$status->statusMsg}}</span></button>
  </div>
</div>
</div>
@else
<div class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<p>One fine body</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

@endif
@endsection

@section('myJs')
<script>
	$("button[role='delete']").click(function(){
		$.ajax({
			type: "POST",
			url: "http://{{$WEB_IP}}/cart/delete",
			dataType:'json',
			data:{
			@if(0==$status->statusId)
				pdtName:"{{$cartProducts[0]['name']}}",
				pdtId:{{$cartProducts[0]['id']}}
			@endif
			},
			/*浏览器删除完相应的cookie信息后，便可以把这本书的显示信息删除
			 否则，删除失败
			 */
			success:function(msg){
				//alert(msg.result.pdtInfo.length);
				var cartHtml='';
				if(msg.statusMsg>0){
					for(var i=0;i<msg.result.pdtInfo.length;i++){
						cartHtml=cartHtml+"<div class='media'>"+
								 "<div class='media-left'>"+
								 "<a href='#'>"+
								"<img class='media-object' src='http://{{$WEB_IP}}/images/"+msg.result.pdtInfo[i].preview+"' alt='...'/>"+
								'</a>'+
								'</div>'+
								"<div class='media-body'>"+
								"<h4 class='media-heading'>"+msg.result.pdtInfo[i].name+"</h4>"+
								"<p>单价："+msg.result.pdtInfo[i].price+"</p>"+
								"<p>数量："+'2'+"</p>"+
								"<div class='btn-group'>"+
								"<button role='delete' type='button' class='btn btn-success'>"+
								" 删除"+
								"</button>"+
								"</div>"+
								"</div>"+
								"</div>";
					}
					$("button[role='pay'] .badge").html(msg.statusMsg);
				}else{
					$("button[role='pay'] .badge").html('0');
				}
				if($('div').hasClass('media')){
					$('.media').empty();
					$('div').remove('.media');
				}
				$('.container').append(cartHtml);				
			}
		});
	});
	$("button[role='ctnBuy']").click(function(){
		window.location.href='http://{{$WEB_IP}}/category';
	});
	$("button[role='pay']").click(function(){
		window.location.href='http://{{$WEB_IP}}/order/pay/{{$bkCart}}';
	});
</script>
@endsection
