@extends('master')

@section('title','书籍列表')

@section('content')
<link href='http://{{$WEB_IP}}/css/category.css' rel='stylesheet' type='text/css'/>
<div class='container'>
<div class="parentCategory btn-group btn-group-justified" role="group">
	<div class="btn-group" >
		<button type="button" class=" btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">C/C++ <span class="caret"></span>
		</button>
		<ul class="dropdown-menu col-xs-12">
			<li name='cpp'><a>C/C++</a></li>
			<li role="separator" class="divider"></li>
			<li name='webdevelop'><a>Web开发</a></li>
			<li role="separator" class="divider"></li>
			<li name='mysql'><a>MySQL</a></li>
			<li role="separator" class="divider"></li>
			<li name='linux'><a>Linux</a></li>
		</ul>
	</div>
</div>
<hr/>
<div class="childCpp btn-group btn-group-justified" role="group">
	<div class="btn-group" >
		<button type="button" class=" btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">QT<span class="caret"></span>
		</button>
		<ul class="dropdown-menu col-xs-12">
			<li name='qt'><a>QT</a></li>
			<li role="separator" class="divider"></li>
			<li name='mfc'><a>MFC</a></li>
		</ul>
	</div>
</div>
<div class="childWeb btn-group btn-group-justified" role="group">
	<div class="btn-group">
		<button type="button" class=" btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PHP<span class="caret"></span>
		</button>
		<ul class="dropdown-menu col-xs-12">
			<li name='php'><a>PHP</a></li>
			<li role="separator" class="divider"></li>
			<li name='javascript'><a>JavaScript</a></li>
		    <li role="separator" class="divider"></li>
			<li name='html'><a>Html</a></li>
			<li role="separator" class="divider"></li>
			<li name='css'><a>CSS</a></li>
		</ul>

	</div>
</div>
</div>
@endsection

@section('myJs')
<script>
	function postChildCate(CateName){
		$.ajax({
			type: "POST",
			url: "http://{{$WEB_IP}}/category",
			dataType:'json',
			data:{catename:CateName},
			success: function(msgs){
				var proHtml='';
				for(var i=0;i<msgs.result.length;i++){
					proHtml=proHtml+'<div class="media">'+
							'<div class="media-left">'+
							'<a href="#">'+
							'<img class="media-object" src="http://{{$WEB_IP}}/images/'+msgs.result[i].preview+'" alt="...">'+
							'</a>'+
							'</div>'+
							'<div class="media-body">'+
							'<a href="http://{{$WEB_IP}}/product/'+msgs.result[i].id+'">'+
							'<h4 class="media-heading">'+msgs.result[i].name+'<span>￥'+msgs.result[i].price+'</span></h4>'+
							'<p>'+
							msgs.result[i].summary+
							'</p>'+
							'</a>'+
							'</div>'+
							'</div>';
				}
				if($('div').hasClass('media')){
					//empty这一个函数只能删除节点里面的内容，并不能删除接点，要用remove才能删除节点，但它不删除内容
					//所以要双管齐下
					$('.media').empty();
					$('div').remove('.media');
				}
				$('.container').append(proHtml);				
			},
			error:function(msg){
				document.write(msg.message);    
			}

		});
	}
	$('.parentCategory ul li').click(function(){
		$('.parentCategory button').html($(this).html());
		switch($(this).attr('name')){
			case 'cpp':
				$('.childWeb').css('display','none');
				$('.childCpp').css('display','block');
				break;
			case 'webdevelop':
				$('.childCpp').css('display','none');
				$('.childWeb').css('display','block');
				break;
			default:
				$('.childCpp').css('display','none');
				$('.childWeb').css('display','none');
				postChildCate($(this).attr('name'));		
		}
	});
	//只有这两个一级目录才有子目录
	$('.childCpp ul li').click(function(){
		$('.childCpp button').html($(this).html());
		//alert($(this).attr('name'));
		postChildCate($(this).attr('name'));
	});
	$('.childWeb ul li').click(function(){
		$('.childWeb button').html($(this).html());
		postChildCate($(this).attr('name'));
	});
</script>
@endsection
