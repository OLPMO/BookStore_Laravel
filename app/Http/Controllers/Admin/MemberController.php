<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Session;
use App\Http\Controllers\MyConfig;
use App\MyClasses\StatusMessage;
use App\Entity\Member;
//为了与侧边栏的管理菜单相对应，所以把分类管理列进了产品控制器里
class MemberController extends Controller{
	public function MemberListView(){
		$members=Member::all();
		return view('admin.member') ->with('members',$members)
									->with('WEB_IP',MyConfig::WEB_IP);
	}
	//封号，至于禁止用户哪些操作这里不会涉及，可根据具体情况而定。
	//当然也可以建立更详细的权限管理制度，这样便可以禁止用户不同权限（更人性化，也更常用）
	public function DisableMember(Request $request){
		$status=new StatusMessage();
		$member=Member::find($request->member_id);
		if(empty($member)){
			$status->statusId=1;
			$status->statusMsg='该用户不存在';
			return $status->toJson();
		}
		$member->disabled=1;
		$member->save();
		$status->statusId=0;
		$status->statusMsg='操作成功';
		return $status->toJson();
	}
	public function AbleMember(Request $request){
		$status=new StatusMessage();
		$member=Member::find($request->member_id);
		if(empty($member)){
			$status->statusId=1;
			$status->statusMsg='该用户不存在';
			return $status->toJson();
		}
		$member->disabled=0;
		$member->save();
		$status->statusId=0;
		$status->statusMsg='操作成功';
		return $status->toJson();
	}
}
