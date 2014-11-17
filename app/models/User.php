<?php
/*     
	前台用户表
	表结构：(aid, name)  
*/
class User extends Eloquent{

	public $timestamps = false;

	protected $table = 'front_user';


}