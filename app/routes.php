<?php


Route::get('/', function()
{

	return View::make('hello');
});

Route::get('getMenus', 'ShopController@getMenus');



Route::get('users', function()
{
    return 'Users!';
});