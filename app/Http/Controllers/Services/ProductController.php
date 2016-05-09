<?php
namespace App\Http\Controllers\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Entity\Member;
use App\Entity\Product;
use App\Entity\Pdt_Content;
use App\Entity\Pdt_Images;
use App\Http\Controllers\MyConfig;
use App\MyClasses\StatusMessage;
class ProductController extends Controller{
	private $reMsg;
	function __construct(){
		$this->reMsg=new StatusMessage;
	}
	public function ProductView(Request $request){
		//要返回所有轮播图片(pdt_image)，产品具体信息（pdt_content），价格
		//名字，简介（product）这些信息
		$products= new Product();
		$pdtContents=new Pdt_Content();
		$pdtAllImages=new Pdt_Images();
		$product=$products->where('id',$request->product_id)->first();
		$pdtContent=$pdtContents->where('product_id',$product->id)->first();
		$pdtImages=$pdtAllImages->where('product_id',$product->id)->get();
		$arrPdtImages=array();
		for($i=0;$i<count($pdtImages);$i++){
			$arrPdtImages[]=$pdtImages[$i]->image_path;
		}
		return   view('product')->with('WEB_IP',MyConfig::WEB_IP)
								->with('product',$product)
								->with('arrPdtImages',$arrPdtImages)
								->with('pdtContent',$pdtContent->content);
	}
}
