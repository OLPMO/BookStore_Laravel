<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Session;
use App\Http\Controllers\MyConfig;
use App\Entity\Category;
use App\Entity\Product;
class IndexController extends Controller{
	public function IndexView(Request $request){
		$categories=Category::all();
		$pareCategory=array();
		$firsCategory=array();
		$i=0;
		$recoNum=0;
		foreach($categories as $category){	
			$recoNum++;
			if(!empty($category->parent_id)){
				$cateInfo[$category->id]['pareName']=Category::find($category->parent_id)->name;
			}else{//没有一级目录，则其本身为一级目录
				$firsCategory[$i]['cateName']=$category->name;
				$firsCategory[$i]['cateId']=$category->id;
				$i++;
			}
			$cateInfo[$category->id]['pdtNum']=Product::where('category_id',$category->id)->count();		
		}
		return	 view('admin.backstage')->with('categories',$categories)
											->with('recoNum',$recoNum)
											->with('cateInfo',$cateInfo)
											->with('firsCategory',$firsCategory)
											->with('WEB_IP',MyConfig::WEB_IP);	
	}
}
