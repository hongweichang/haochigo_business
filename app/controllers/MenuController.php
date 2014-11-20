<?php

/**
 * 菜单管理控制器
 *
 * addMenu()			添加一个菜单
 * delMenu()			删除某个菜单
 * getMenu_comment()	获取某个菜单的评论信息
 * modifyMenu()			修改某一个菜单
 */
class MenuController extends BaseController {

	/**
	 * 添加一个菜单
	 *
	 * 对应API：
	 * 请求类型：POST
	 */
	public function addMenu(){
		$menu = new Menu;
		$menu->shop_id = Input::get('shop_id');
		$menu->group_id = Input::get('group_id');
		$menu->title = Input::get('title');
		$menu->price = Input::get('price');
		$menu->pic = Input::get('pic');
		$menu->pic_small = Input::get('pic_small');
		$menu->state = Input::get('state');
		$menu->save();
	}
	
	/**
	 * 修改某个菜单
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return array 执行状态信息
	 */
	public function modifyMenu(){
		$menu_id = Input::('menu_id');
		$data = Input::('data');		// 把要修改的信息弄成一个数组
		Menu::where('id', $menu_id)->update($data);
	}

	/**
	 * 删除菜单
	 *
	 * 对应API
	 * 请求类型：POST
	 * @return array 执行状态消息
	 */
	public function delMenu(){
		$menu_id = Input::get('menu_id');
		Menu::delete($menu_id);				// 应该是可以直接根据主键进行删除的
	}

	/**
	 * 添加菜单
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return array 执行状态消息
	 */
	public funciton addMenu(){
		$data = array();

		// 在添加菜单的时候有很多默认字段这里就不写了
		$data['shop_id'] = Input::get('shop_id');
		$data['title'] = Input::get('title');
		$data['group_id'] = Input::get('group_id');		// 如果未分类就默认为0
		$data['intro'] = Input::get('intro');
		$data['price'] = Input::get('price');
		$data['pic'] = Input::get('pic');	// 默认为空
		$data['addtime'] = time();	// 就是当前的时间了
		$data['num_today'] = Input::get('num_today');	//每日总量，可设置为无上限
		$data['state'] = 1;			// 该商品的状态，默认为1表示有货,1表示接受预定

		Menu::insert($data);
	}


	/**
	 * 获取某个菜单的评论信息
	 *
	 * 对应API：shop/Ajax/获取一个商品的评论
	 * @param  integer $menu_id 菜单ID
	 * @return array 商品的评论信息
	 */
	public function getMenu_comment($menu_id){
		$data = array();
		$shop_level = array('level_5' => 0, 'level_4' => 0, 'level_3' => 0, 'level_2' => 0, 'level_1' => 0);
		$comment_body = array();

		$ids = CommentMenu::where('menu_id', '=', $menu_id)->lists('id');	
		foreach($ids as $id){
			$u_comment = array();
			$comment = CommentMenu::find($id);
			$person = User::where('front_uid', '=', $comment->front_uid)->lists('nickname');
			$u_comment['comment_person'] = $person[0];
			$u_comment['comment_date'] = $comment->time;
			$u_comment['comment_level'] = $comment->value;
			$u_comment['comment_content'] = $comment->content;
			
			$shop_level['level_'.(ceil($u_comment['comment_level']))]++;

			array_push($comment_body, $u_comment);
		}

		$shop_total = $shop_level['level_5'] + $shop_level['level_4'] + $shop_level['level_3'] + $shop_level['level_2'] + $shop_level['level_1']; 

		array_push($data, $shop_level);
		array_push($data, $shop_total);
		array_push($data, $comment_body);
		return $data;
	}
}
