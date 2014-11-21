<?php


Route::get('/', function()
{

	return View::make('hello');
});

Route::get('getMenus', 'ShopController@getMenus');
Route::get('geohashSet', 'ShopController@geoHashSet');
Route::get('geohashGet', 'ShopController@geoHashGet');



Route::get('users', function()
{
    return 'Users!';
});