<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/login', 'Services\MemberController@LoginView');

//以下是关于注册方法面的路由
Route::get('/signup','Services\SignUpController@SignUpView');
Route::post('/signup/signinfo','Services\SignUpController@ValidateSignInfo');
Route::get('/signup/signupvalid','Services\SignUpController@SignUpValidView');
Route::post('/signup/confirmemail','Services\SignUpController@ConfirmEmailInfo');

//以下是关于书籍分类方面的路由
Route::get('/category','Services\CategoryController@CategoryView');
Route::post('/category','Services\CategoryController@CategoryList');
//Route::get('/category/test','Services\CategoryController@CategoryList');

//以下是写关于产品内容方面的路由
Route::get('/product/{product_id}','Services\ProductController@ProductView');

//以下为购物车路由
Route::get('/cart','Services\CartController@CartView');
Route::post('/addcart','Services\CartController@AddCart');
Route::post('/cart/delete','Services\CartController@DeleteCart');


//以下问后台管理路由
Route::get('/admin','Admin\IndexController@IndexView');
Route::get('/admin/category','Admin\ProductController@CategoryView');
Route::post('/admin/addcate','Admin\ProductController@AddCategory');
Route::post('admin/editcate','Admin\ProductController@EditCategory');
Route::post('admin/delcate','Admin\ProductController@DeleteCategory');

Route::get('/admin/product','Admin\ProductController@ProductView');
Route::get('/admin/product/add','Admin\ProductController@AddProductView');
Route::post('/admin/product/add','Admin\ProductController@AddProduct');
Route::get('/admin/product/edit/{product_id}','Admin\ProductController@EditProductView');
Route::post('/admin/product/delete','Admin\ProductController@DeleteProduct');

Route::get('/admin/member','Admin\MemberController@MemberListView');
Route::post('/admin/member/disable','Admin\MemberController@DisableMember');
Route::post('/admin/member/able','Admin\MemberController@AbleMember');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //以下由于用到session记录状态，所以要在这里面写路由
	Route::post('/login/check','Services\MemberController@CheckLogin');

});
Route::group(['middleware' => ['web','check.login']], function () {
	Route::get('/order/pay/{cartcontent}','Services\OrderController@PayView');

	Route::post('/order/pay/delete/{orderid}','Services\OrderController@DeleteOrderItem');
});

