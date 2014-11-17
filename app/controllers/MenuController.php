<?php

/**
 * 菜单的各个功能
 */
class MenuController extends BaseController {

	# 添加菜单
	# 删除菜单
	# 修改菜单
	# 获取某个店铺的菜单的分类
	# 获取某个店铺的菜单
	# 菜单的评价
	# 获取菜单的评价


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

		$ids = Comment::where('menu_id', '=', $menu_id)->lists('id');	
		foreach($ids as $id){
			$u_comment = array();
			$comment = Comment::find($id);
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
