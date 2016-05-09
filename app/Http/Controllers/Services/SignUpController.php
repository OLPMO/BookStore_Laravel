<?php
namespace App\Http\Controllers\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Entity\TempEmail;
use Mail;
use App\Entity\Member;
use App\MyClasses\StatusMessage;
use App\Http\Controllers\MyConfig;
class SignUpController extends Controller{
	private $reMsg;
	function __construct(){
		$this->reMsg=new StatusMessage;
	}
	public function SignUpView(){
		return view('signup')->with('WEB_IP',MyConfig::WEB_IP);
	}
	public function SignUpValidView(){
		return view('signupvalid')->with('WEB_IP',MyConfig::WEB_IP);
	}
	public function ValidateSignInfo(Request $request ){
		//先插入数据库，再发送验证信息
		$newMember=new Member;
		//request的only函数返回的是数组
		$uemail=$request->only('uEmail')['uEmail'];
		$upass=$request->only('uPass')['uPass'];
		$uconpass=$request->only('uConfirmPass')['uConfirmPass'];
		$uname=$request->only('uName')['uName'];
		if(empty($uname)||empty($uemail)||empty($upass)||empty($uconpass)){
			$this->reMsg->statusId=1;
			$this->reMsg->statusMsg='缺少必填信息，验证失败！';
			return $this->reMsg->toJson();	
		}else{
			if($upass!=$uconpass){
				$this->reMsg->statusId=2;
				$this->reMsg->statusMsg='两次输入的密码不一致';
				return $this->reMsg->toJson();
			}
			$match='/^[\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10}$/i';
			$v = trim($uemail);
			if(!preg_match($match,$v)){
				$this->reMsg->statusId=3;
				$this->reMsg->statusMsg='请输入正确的邮箱地址';
				return $this->reMsg->toJson();
			}
			//查重后在插入数据库
			if($newMember->where('email',$uemail)->count()){
				$this->reMsg->statusId=4;
				$this->reMsg->statusMsg='此邮箱地址已注册，请勿重复注册！';
				return $this->reMsg->toJson();
			}	
			$newMember->nickname=$uname;
			$newMember->email=$uemail;
			$newMember->password=md5('_pass+'+$upass);
			$newMember->save();
		}
		$ranstr="0123456789abcdefghijklnmopqrstuvwxyz";
		$myran='';
		$strmax=strlen($ranstr)-1;
		for($i=0;$i<25;$i++){
			$myran=$myran.$ranstr[rand(0,$strmax)];
		}
		//生成验证email的连接
		$regLink='http://localhost/signup/signupvalid?memberid='.$newMember->where('email',$uemail)->first()->id.'&myran='.$myran;
		//要把临时验证信息插入email验证的临时表
		$validEmail=new TempEmail;
		$validEmail->member_id=$newMember->where('email',$uemail)->first()->id;
		$validEmail->validateinfo=$myran;
		$validEmail->deadline=date('Y-m-d H:i:s',time()+24*60*60);
		$validEmail->save();
		$myMsg=array('email'=>$uemail,'content'=>$regLink,'name'=>$uname);	
		Mail::send('emailreminder', ['user' => $myMsg], function($m)use($myMsg){

		$m->to($myMsg['email'],'尊敬的'.$myMsg['name'].'用户')->subject('网上书店验证邮件');
		});
		$this->reMsg->statusId=0;
		$this->reMsg->statusMsg='已发送邮件请注意查收！';
		return $this->reMsg->toJson();

	}
	public function ConfirmEmailInfo(Request $request){
		$validEmail=new TempEmail;
		$validMember=$validEmail->where('member_id',$request->memberid)->first();
		$validateInfo=$validMember->getAttributes()['validateinfo'];//获取具体的字段值
		$deadline=strtotime($validMember->deadline);
		if(time()<$deadline&&$validateInfo==$request->myran){
			$member=new Member();
			$member->where('id',$request->memberid)->update(['active'=>1]);
			$this->reMsg->statusId=0;
			$this->reMsg->statusMsg='验证成功';
			return $this->reMsg->toJson();
			//写接口程序时不要写跳转信息，因为这个程序有可能供安卓调用
			/*echo'<html>';
			echo"<meta http-equiv='refresh' content='3;url=http://localhost/login'>";
			echo'</html>';*/

		}else{
			$this->reMsg->statusId=5;
			$this->reMsg->statusMsg='邮箱验证失败！';
			return $this->reMsg->toJson();
		}
	}
}
