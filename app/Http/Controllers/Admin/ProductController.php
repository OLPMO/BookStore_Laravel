<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Session;
use App\Http\Controllers\MyConfig;
use App\MyClasses\StatusMessage;
use App\MyClasses\Return2Page;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Pdt_Content;
use App\Entity\Pdt_Images;
//为了与侧边栏的管理菜单相对应，所以把分类管理列进了产品控制器里
class ProductController extends Controller{
	public function CategoryView(Request $request){
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
	public function AddCategory(Request $request){
		$status=new StatusMessage();
		//缺了校验输入信息，真的要作为项目时必须对输入进行校验
		$category=Category::where('name',$request->name)->first();
		//没有数据便是添加，有数据便是修改
		if(0!=count($category)){
			$status->statusId=1;
			$status->statusMsg='该类别已存在。';
		}else{
			$category=new Category();
		}
		$category->name=$request->name;
		$category->parent_id=$request->parentId;	
		$category->save();
		$status->statusId=0;
		$status->statusMsg='添加成功';
		return $status->toJson();
	}
	public function EditCategory(Request $request){
		$status=new StatusMessage();
		$category=Category::find($request->id);
		if(0!=count($category)&&empty($request->name)){
			//获取数据
			$status->statusId=0;
			unset($category->created_at);
			unset($category->updated_at);
			$status->statusMsg=$category;
		}else if(0!=count($category)&&!empty($request->name)){
			//修改数据
			$category->name=$request->name;
			$category->parent_id=$request->parentId;
			$category->save();
			$status->statusId=0;
			$status->statusMsg='修改成功';
		}else{
			$status->statusId=2;
			$status->statusMsg='修改失败';
		}
		return $status->toJson();
	}
	public function DeleteCategory(Request $request){
		$status=new StatusMessage();
		$category=Category::find($request->id);
		$pdtNum=Product::where('category_id',$request->id)->count();
		$childNum=Category::where('parent_id',$request->id)->count();
		if(0==count($category)){
			$status->statusId=3;
			$status->statusMsg='抱歉！该分类不存在';
		}else if(0!=$pdtNum){
			$status->statusId=4;
			$status->statusMsg='该分类下仍有商品，无法删除！';
		}else if(0!=$childNum){
			$status->statusId=5;
			$status->statusMsg='该分类下有子分类，无法删除！';
		}else{
			$category->delete();
			$status->statusId=0;
			$status->statusMsg='删除成功';
		}
		return $status->toJson();
	}
	public function ProductView(Request $request){
		$products=Product::all();
		$pdtNum=$products->count();
		$pdtInfo=array();
		foreach($products as $product){
			$pdtInfo[$product->id]=Category::find($product->category_id)->name;
		}
		return view('admin.product')->with('WEB_IP',MyConfig::WEB_IP)
									->with('pdtNum',$pdtNum)
									->with('pdtInfo',$pdtInfo)
									->with('products',$products);
	}
	public function AddProductView(Request $request){
		$categories=Category::all();
		$cateInfo=array();
		$i=0;
		//要把有子目录的一级目录剔除
		foreach($categories as $category){
			$cateInfo[$i]['id']=$category->id;
			$cateInfo[$i]['name']=$category->name;
			$i++;
		//	var_dump($cateInfo);
			$tmp=array_values($cateInfo);
			$cateInfo=$tmp;
			for($j=0;$j<count($cateInfo);$j++){
				if($cateInfo[$j]['id']==$category->parent_id){
					//不会重新排序
					unset($cateInfo[$j]);
					break;
				}
			}
		}
		return view('admin.manageproduct')	->with('cateInfo',$cateInfo)
											->with('WEB_IP',MyConfig::WEB_IP);
	}
	public function AddProduct(Request $request){
		$status=new StatusMessage();
		$product=new Product();
		$pdtContent=new Pdt_Content();
		$imgPath='/var/www/html/my_laravel/public/images/'.date('Ymd');
		$imgRelativePath=date('Ymd');
		if(!is_dir($imgPath)){
			mkdir($imgPath);
		}
		//此处将省略输入信息的校验，正式项目必不可少
		if(!empty($request->pdtName)){
			$prvImageName=rand(1000,9999).'preview'.(string)rand(100,999);
			for($i=1;$i<4;$i++){
				$crsImageName[$i]=rand(1000,9999).'carousel'.(string)$i.(string)rand(100,999);
			}
		}else{
			$status->statusId=1;
			$status->statusMsg='产品名不能为空';
			return $status->toJson();
		}
		if(empty($request->pdtSummary)){
			$status->statusId=4;
			$status->statusMsg='产品简介不能为空';
			return $status->toJson();
		}
		if('Null'==$request->pdtCate){
			$status->statusId=5;
			$status->statusMsg='类别不能为空';
			return $status->toJson();
		}
		if(empty($request->pdtContent)){
			$status->statusId=6;
			$status->statusMsg='产品介绍不能为空';
			return $status->toJson();
		}
		$product->name=$request->pdtName;
		$product->summary=$request->pdtSummary;
		$product->price=$request->pdtPrice;
		$product->preview=$imgRelativePath.'/'.$prvImageName;
		$cateId=null;
		$cateId=Category::where('name',$request->pdtCate)->first()->id;
		if(empty($cateId)){
			$status->statusId=5;
			$status->statusMsg='该类别不存在';
			return $status->toJson();
		}
		$product->category_id=$cateId;
		$product->save();
		$pdtContent->product_id=$product->id;
		$pdtContent->content=$request->pdtContent;
		$pdtContent->save();
		for($i=1;$i<4;$i++){
			$pdtImage=new Pdt_Images();
			$pdtImage->product_id=$product->id;
			$pdtImage->image_no=$i;
			$pdtImage->image_path=$imgRelativePath.'/'.$crsImageName[$i];
			$pdtImage->save();
		}
		/*
		 *文件最后转移，如果放在前面，那么有时其他内容不合格，文件也会转移
		 *这会造成大量的垃圾文件，当然文件转移其实也不应该和文件校验放在一
		 *起，因为放在一起的话，文件即使不存在，文件名也已经存在数据库了，这
		 *就会出现无效数据的问题。
		 *这个问题很容易解决。出现无效数据时，可以去修改产品信息处修改
		 *所以这个小bug就暂且放在这里做个记号
		 */	
		if($request->hasFile('prvImage')){
			$request->file('prvImage')->move($imgPath,$prvImageName);
		}else{
			$status->statusId=2;
			$status->statusMsg='预览图不能为空';
			return $status->toJson();
		}
		for($i=1;$i<4;$i++){
			if($request->hasFile('crsImage'.(string)$i)){
				$request->file('crsImage'.(string)$i)->move($imgPath,$crsImageName[$i]);
			}else{
				$status->statusId=3;
				$status->statusMsg='轮播图必须有且仅有3张';
				return $status->toJson();
			}
		}
		$status->statusId=0;
		$status->statusMsg='添加成功';
		return $status->toJson();
	}
	public function EditProductView(Request $request){
		$categories=Category::all();
		$cateInfo=array();
		$arrPdtImages=array();
		$i=0;
		//要把有子目录的一级目录剔除
		foreach($categories as $category){
			$cateInfo[$i]['id']=$category->id;
			$cateInfo[$i]['name']=$category->name;
			$i++;
			$tmp=array_values($cateInfo);
			$cateInfo=$tmp;
			for($j=0;$j<count($cateInfo);$j++){
				if($cateInfo[$j]['id']==$category->parent_id){
					//不会重新排序
					unset($cateInfo[$j]);
					break;
				}
			}
		}	
		$product=Product::find($request->product_id);
		$pdtContent=Pdt_Content::where('product_id',$request->product_id)->first();
		$pdtImages=Pdt_Images::where('product_id',$request->product_id)->get();
		$i=1;
		foreach($pdtImages as $pdtImage){
			$arrPdtImages[$i]=$pdtImage->image_path;
			$i++;
		}
		return view('admin.manageproduct')	->with('cateInfo',$cateInfo)
											->with('product',$product)
											->with('arrPdtImages',$arrPdtImages)
											->with('pdtContent',$pdtContent)
											->with('WEB_IP',MyConfig::WEB_IP);

	}
	public function DeleteProduct(Request $request){
		$status=new StatusMessage();
		$product=Product::find($request->product_id);
		if(empty($product)){
			$status->statusId=1;
			$status->statusMsg='该产品不存在';
			return $status->toJson();
		}
		$product->delete();
		$pdtContent=Pdt_Content::where('product_id',$request->product_id)->first();
		if(!empty($pdtContent)){
			$pdtContent->delete();
		}
		$pdtImages=Pdt_Images::where('product_id',$request->product_id)->get();
		if(!empty($pdtImages)){
			foreach($pdtImages as &$pdtImage){
				$pdtImage->delete();
			}
		}
		$status->statusId=0;
		$status->statusMsg='删除成功';
		return $status->toJson();
	}
}
