<?php

/*
	收藏的菜单
	表结构：(id, user_id, menu_id)
 */
class Menugroup extends Eloquent{

	public $timestamps = false;

	protected $table = 'menu_group';


}