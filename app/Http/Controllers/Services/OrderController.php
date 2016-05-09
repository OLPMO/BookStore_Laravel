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
use App\Entity\Member;
use App\Entity\Order;
use App\Entity\OrderItems;
class OrderController extends Controller{
	/* 
	 * 先把购物车的东西同步到订单中，由于此项目没有写多选按钮，故必须
	 * 把购物车所有的物品都同步到订单中，这样做不太友好，因为有的物品是
	 * 客户看上，但并不打算立即购买的。
	 */
	//以下为客户要提交的订单，但要在30分钟内付款即可
	public function PayView(Request $request){
		//读取cookie中所有的购物车信息以及商品名称和预览图，并返回给视图
		//要返回总计的价格
		$status=new StatusMessage();
		//无法获取，需要用get方法将参数传递过来
		//$bkCart=$request->cookie('bkCart');
		$bkCart=$request->cartcontent;	
		$member=$request->session()->get('member', '');
		if(empty($bkCart)){
			$status->statusId=1;
			$status->statusMsg=0;
			return view('pay')->with('WEB_IP',MyConfig::WEB_IP)
								->with('totalPrice',0)
								->with('status',$status);
	
		}else{
			$products=new Product();
			$exisFlag=false;
			$cmdySum=0;
			$cmdyTotalPrice=0;
			//把购物车中所有的产品信息
			$payOrderItems=array();
			$cmdyNum=array();
			$orderNum=md5((string)$member->id.$bkCart);
			$exisOrders=Order::where('order_num',$orderNum)->get();
			if(0!=count($exisOrders)){
				foreach($exisOrders as $exisOrder){
					if(0==$exisOrder->status&&time()<(strtotime($exisOrder->created_at)+30*60)){
						$exisFlag=true;
						$order=$exisOrder;
						break;
					}	
				}
			}
			if(!$exisFlag){
				//如果没同步到订单中便同步，如何订单已存在，直接遍历即可
				$order=new Order();
				$order->member_id=$member->id;
				$order->status=0;
				$order->order_num=$orderNum;
				$order->save();
				$arrBkCart=explode(',',$bkCart);
				foreach($arrBkCart as $commodity){
					$tmp=explode(':',$commodity);
					$productId=(int)$tmp[0];
					$product=$products->where('id',$productId)->first();
					$orderItem=new OrderItems();
					$orderItem->order_id=$order->id;
					$orderItem->order_num=$orderNum;
					$orderItem->product_id=$product->id;
					$orderItem->product_num=(int)$tmp[1];
					$orderItem->price=$product->price;
					$orderItem->snapshot=json_encode($product,JSON_UNESCAPED_UNICODE);
					$orderItem->save();
					//存储产品的具体信息
					$payOrderItems[]=$product;
					//$productNum=(int)$tmp[1];
					$cmdySum+=(int)$tmp[1];
					$cmdyTotalPrice+=((int)$tmp[1])*$product->price;
					$cmdyNum[$productId]=(int)$tmp[1];
				}
				$status->statusId=0;
				$status->statusMsg=$cmdySum;
				$order->total_price=$cmdyTotalPrice;
				$order->save();
			}else{
				//订单已存在，遍历订单号对应的product即可
				$orderItems=OrderItems::where("order_id",$order->id)->get();
				if(0!=count($orderItems)){
					foreach($orderItems as $orderItem){
						//对象
						$pdtSnapShot=json_decode($orderItem->snapshot);
						$payOrderItems[]=$pdtSnapShot;
						$cmdyNum[$pdtSnapShot->id]=$orderItem->product_num;
						$cmdySum+=$orderItem->product_num;
					}
					$cmdyTotalPrice=$order->total_price;
					$status->statusId=0;
					$status->statusMsg=$cmdySum;
				}
			}
		}
		return	view('pay')->with('status',$status)
							->with('WEB_IP',MyConfig::WEB_IP)
							->with('totalPrice',$cmdyTotalPrice)
							->with('cmdyNum',$cmdyNum)
							->with('orderId',$order->id)
							->with('payOrderItems',$payOrderItems);
		 
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
				//$productNum=(int)$tmp[1];
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
	public function DeleteOrderItem(Request $request){
		$re2Page=new Return2Page();
		$orderItem=null;
		$orderItem=OrderItems::where('order_id',$request->orderid)->where('product_id',$request->mark)->first();
		if(empty($orderItem)){
			$re2Page->statusId=1;
			$re2Page->statusMsg='该商品不存在';
			return $re2Page->toJson();
		}else{
			//如果该商品在订单中，删除该商品并返回订单中其他所有的商品信息
			//订单中的总金额也应该进行相应的修该
			$orderItem->delete();
			$pdtInfo=array();
			$orderItems=OrderItems::where('order_id',$request->orderid)->get();
			$i=0;
			$totalPrice=0;
			foreach($orderItems as $item){
				$totalPrice+=($item->price*$item->product_num);
				$pdtInfo[$i]=json_decode($item->snapshot);
				//把不许要返回的东西删除，节省带宽
				unset($item->snapshot);
				unset($pdtInfo[$i]->summary);
				$i++;
			}
			$order=Order::where('id',$request->orderid)->first();
			$order->total_price=$totalPrice;
			$order->save();
			$re2Page->result=array('orderItems'=>$orderItems,'pdtInfo'=>$pdtInfo);
			$re2Page->statusId=0;
			//返回总计金额
			$re2Page->statusMsg=$totalPrice;
			return $re2Page->toJson();
		}
	}
}
