<?php
namespace App\Http\Controllers\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\MyClasses\StatusMessage;
use App\MyClasses\Return2Page;
use App\Entity\Category;
use App\Entity\Product;
use App\Http\Controllers\MyConfig;
class CategoryController extends Controller{
	public function CategoryView(){
		return view('category')->with('WEB_IP',MyConfig::WEB_IP);
	}
	public function CategoryList(Request $request){
		$re2Page=new Return2Page;
		$categories=new Category;
		$products=new Product;
		$proInfos=array();
		$category=$categories->where('name',strtolower($request->catename))->first();
		$cateId=$category->getAttributes()['id'];
		$cateParId=$category->getAttributes()['parent_id'];
		$childCategories=$categories->where('parent_id',$cateId)->get();
		//如果parentId为空且含有子目录，那么应该列出子目录下的所有产品
		if(empty($cateParId)&&!empty($childCategories->first())){
			//要把所有产品信息（如name，preview，summary，price）聚合在一起
			foreach ($childCategories as $childCategory) {
				$childCateProducts=$products->where('category_id',$childCategory->id)->get();
				foreach($childCateProducts as $childCateProduct){
					$proInfos[]=$childCateProduct->getAttributes();
				}
			}
		}else{//选中的目录为子目录, 以及没有子目录的一级目录
			$cateProducts=$products->where('category_id',$cateId)->get();
			foreach($cateProducts as $cateProduct){
				$proInfos[]=$cateProduct->getAttributes();
			}
		}
		mb_internal_encoding("UTF-8");
		for($i=0;$i<count($proInfos);$i++){
			$proInfos[$i]['summary']=mb_substr($proInfos[$i]['summary'],0,150).'```````';
		}
		if(empty($proInfos)){
			$re2Page->result=array();
			$re2Page->statusId=1;
			$re2Page->status='no result';
		}else{
			$re2Page->result=$proInfos;
			$re2Page->statusId=0;
			$re2Page->statusMsg='success';
		}
		return $re2Page->toJson();

	}
}
