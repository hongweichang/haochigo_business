<?php


Route::get('/', function()
{
	return View::make('hello');
});


Route::get('test', 'TestController@show');

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