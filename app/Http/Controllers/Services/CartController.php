<?php
namespace App\Http\Controllers\Services;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Entity\Product;
use App\MyClasses\Return2Page;
use App\MyClasses\StatusMessage;
use App\Http\Controllers\MyConfig;
class CartController extends Controller{
	public function CartView(Request $request){
		//读取cookie中所有的购物车信息以及商品名称和预览图，并返回给视图
		$status=new StatusMessage();
		$bkCart=$request->cookie('bkCart');
		if(empty($bkCart)){
			$status->statusId=1;
			$status->statusMsg=0;
			return view('cart')	->with('WEB_IP',MyConfig::WEB_IP)
								->with('status',$status);
		}else{
			$products=new Product();
			//把购物车中所有的产品信息
			$cartProducts=array();
			$cartMessages=array();
			$arrBkCart=explode(',',$bkCart);
			$cmdySum=0;
			foreach($arrBkCart as $commodity){
				$tmp=explode(':',$commodity);
				$productId=(int)$tmp[0];
				$product=$products->where('id',$productId)->first();
				$cartProducts[]=$product->getAttributes();
				$cmdySum+=(int)$tmp[1];
				$cartMessages[$productId]=(int)$tmp[1];
			}
			$status->statusId=0;
			$status->statusMsg=$cmdySum;
				
		}
		return	view('cart')->with('status',$status)
							->with('WEB_IP',MyConfig::WEB_IP)
							->with('bkCart',$bkCart)
							->with('cartMessages',$cartMessages)
							->with('cartProducts',$cartProducts);
	
	}
	//返回的是购物车的cookie
	public function AddCart(Request $request){
		//获取cookie信息，判断当前点击的书籍是否在购物车中，有就加一，没有就添加该物品，获取购物车中物品总数并返回就可以了
		//cookie以产品id：数量对来存储
		$re2Page=new Return2Page();
		$bkCart=$request->cookie('bkCart');
		if(empty($bkCart)){
			//购物车为空的操作
			$re2Page->statusId=0;
			if('addcart'==$request->action){
				$re2Page->statusMsg=1;
				$re2Page->result=(string)($request->productId).':1';
			}else{
				$re2Page->statusMsg=0;
				$re2Page->result=NULL;
			}
			//因为cookie是存储在客户端当中，所以要返回给客户端才能存进去
			return response($re2Page->toJson())->withCookie('bkCart',$re2Page->result);
		}else{
			//购物车不为空的操作有两中可能性 1-添加的物品在购物车内 2-添加的物品不再购物车内
			$existFlag=false;
			$arrBkCart=explode(',',$bkCart);
			$cmdySum=0;
			$re2Page->statusId=1;
			foreach($arrBkCart as &$commodity){
				$tmp=explode(':',$commodity);
				$productId=(int)$tmp[0];
				$cmdySum+=(int)$tmp[1];
				if('addcart'==$request->action&&($request->productId==$productId)){
					$re2Page->statusId=0;
					$tmp[1]=(int)$tmp[1]+1;
					$cmdySum+=1;
					$commodity=implode(':',$tmp);
					$existFlag=true;
				}
			}
			if(!$existFlag&&'addcart'==$request->action&&!empty($request->productId)){
				$arrBkCart[]=(string)$request->productId.':1';				
			}
			$re2Page->result=implode(',',$arrBkCart);
			$re2Page->statusMsg=$cmdySum;
			return response($re2Page->toJson())->withCookie('bkCart',$re2Page->result);
		}
	}
	public function DeleteCart(Request $request){
		$re2Page=new Return2Page();
		$bkCart=$request->cookie('bkCart');
		if(empty($bkCart)){
			$re2Page->statusId=1;
			$re2Page->statusMsg='failed';
			return $re2Page->toJson();
		}else{
			$products=new Product();
			$cartProducts=array();
			$cartMessages=array();
			$arrBkCart=explode(',',$bkCart);
			$cmdySum=0;
			$re2Page->statusId=1;
			$cntBkCart=count($arrBkCart);
			for($i=0;$i<$cntBkCart;$i++){
				$tmp=explode(':',$arrBkCart[$i]);
				$productId=(int)$tmp[0];
				$cmdySum+=(int)$tmp[1];
				//每一个产品id对应的产品数量
				if($request->pdtId==$productId){
					$cmdySum-=(int)$tmp[1];
					//删除后会重新排列数组内的元素，所以下一次循环进来就会少遍历一个元素
					array_splice($arrBkCart,$i,1);
					//抵消for循环里面的$i++操作
					$i--;
					//因为数组少了一样内容，所以要把循环次数减一
					$cntBkCart--;
				}else{
					$product=$products->where('id',$productId)->first();
					//其中包含每个产品的单价和书名
					$cartProducts[]=$product->getAttributes();
				}
			}
			$re2Page->statusId=0;
			//这一项返回的是购物车中书的总数量
			$re2Page->statusMsg=$cmdySum;
			//要把产品信息（书名，购买数量，单价），买了多少本不同的书返回
			$re2Page->result=array();
			$re2Page->result['bkCart']=implode(',',$arrBkCart);
			$re2Page->result['pdtInfo']=$cartProducts;
			return response($re2Page->toJson())->withCookie('bkCart',$re2Page->result['bkCart']);
		}
	}
}
