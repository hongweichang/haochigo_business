<?php


Route::get('/', function()
{
	return View::make('hello');
});

Route::get('deal/{uid?}', 'PersonController@getRecent_user_deal');
/*

Route::get('test/{uid?}', 'ShopController@my_store');

Route::get('comment/{menu_id?}', 'MenuController@getMenu_comment');
Route::get('list', 'ShopController@get_shop_list');			// 餐厅列表
Route::get('store/{uid?}', 'ShopController@get_my_store');	// 我收藏的店铺/更多餐厅
Route::post('getstore/{uid?}', 'ShopController@get_store');	// 进入收藏的商家的页面
Route::post('cancelstore/{uid?}', 'ShopController@cacel_store');// 取消收藏
Route::get('get_comment/{uid?}', 'ShopController@get_comment');	// 获取商店的评论信息


Route::get('shop/{uid?}', function()
{
	return '这是某个商家的页面';
});

Route::get('profile/favor_restaurant', function(){
	return '我收藏的餐厅页面';
});

/*
Route::get('test', function()
{
	return View::make('test');
});
*/


Route::get('users', function()
{
    return 'Users!';
});