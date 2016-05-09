<?php
namespace App\Http\Controllers\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MyClasses\StatusMessage;
use App\Http\Controllers\MyConfig;
use App\Entity\Member;
use App\Entity\Order;
use App\Entity\OrderItems;
class MemberController extends Controller{
	public function LoginView(){
		return view('login')->with('WEB_IP',MyConfig::WEB_IP);
	}
	public function CheckLogin(Request $request){
		$status=new StatusMessage();
		$member = null;
		$uName=$request->get('username');
		//判断它是用手机登录的还是用邮箱登录的
		if(strpos($uName,'@')==false){
			$member=Member::where('phone',$uName)->first();
		}else{
	
			$member=Member::where('email',$uName)->first();
		}
		if(empty($member)){
			$status->statusId=1;
			$status->statusMsg='该用户不存在';
			return $status->toJson();
		}else{
			if($member->password==md5('_pass'+$request->get('password'))){
				$request->session()->put('member',$member);
				$status->statusId=0;
				$status->statusMsg='登录成功';
				return $status->toJson();
			}else{
				$status->statusId=2;
				$status->statusMsg='密码错误';
				return $status->toJson();
			}
		}
		
	}
}
