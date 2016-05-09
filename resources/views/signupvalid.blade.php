@extends('master')
@section('title','邮箱验证')

@section('content')
	
@endsection

@section('myJs')
	<script>
		var paraOfGet=window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		var memberId,myRan;
		var i,tmp;
		for(i=0;i<paraOfGet.length;i++){
			//如果存在memberid这一项参数，就把它分割
			if(paraOfGet[i].indexOf('memberid')!=-1){
				memberId=paraOfGet[i].split('=')[1];
			}
			if(paraOfGet[i].indexOf('myran')!=-1){
				myRan=paraOfGet[i].split('=')[1];
			}
		}
		$.ajax({
			type: "POST",
			url: "http://192.168.253.1/signup/confirmemail",
			dataType:'json',//参数名区分大小写
			data:{memberid:memberId,myran:myRan,_token:"{{csrf_token()}}"},
			success: function(msg){
				document.write(msg.statusMsg);
				setInterval(function(){
					window.location.href='http://localhost/login';		
						
				},3000);
			},
			error:function(){
			document.write(msg);	
			}
		});
	</script>
@endsection
