<?php


/**
 * 个人管理控制器
 * 
 * cancel_store()		取消收藏的某个菜单
 * get_my_store()		获取我收藏的店铺
 * putMenu_comment()	给某个菜单进行评价
 */
class PersonController extends Controller {

	/**
	 * 我收藏的店铺(这个商家内容与下面的不一样.. →_→)
	 *
	 * 对应API：main/blade/my_store
	 * @param  int $uid 用户的ID
	 * @return array  	结果数组
	 */
	public function get_my_store($uid){
		# 首先要获取这个用户收藏的店铺，最多选取5个
		$stores = Collectshop::where('uid', $uid)->orderBy('uptime', 'desc')->take(5)->lists('shop_id');

		$result = array();
		$result['url'] = array('settings' => 'profile/favor_restaurant');

		$data = array();
		foreach($stores as $store){
			$onestore = array();

			$shop = Shop::find($store);
			$onestore['shop_id'] = $shop->id;
			$onestore['place_id'] = '123';					// ----------------------------------------后期可能是x和y
			$onestore['shop_url'] = 'shop/'.$shop->id;		 	// 点击跳转到相应商家
			$onestore['shop_logo'] = $shop->pic;		  	// 商家的logo图片地址
			$onestore['deliver_time'] = $shop->interval;	// 送货时间间隔
			$onestore['operation_time'] = $shop->operation_time;	// ----------------------------没有开始时间，只有一个时间字符串
			$onestore['shop_name'] = $shop->name;			// 商家名称
			$onestore['shop_type'] = $shop->type;			// 商家类型，以逗号分隔的字符串---------------------------这个还是问一下
			$onestore['shop_level'] = $shop->level;			// 商家评级
			$onestore['order_count'] = $shop->sold_num;		// 订单总量
			$onestore['is_opening'] = $shop->state;			// 营业状态
			$onestore['is_ready_for_orer'] = $shop->reserve;// 是否接受预定

			array_push($data, $onestore);
		}
		$result['data'] = $data;
		return $result;
	}

	/**
	 * 	取消收藏
	 * 	
	 * 对应API：main/AJAX
	 * @return [type] [description]
	 */
	public function cacel_store(){
		$shop_id = Input::get('shop_id');
		$place_id = Input::get('place_id');

		$output = array();
		$output['success'] = 'true';
		$output['state'] = 200;
		$output['nextSrc'] = '';
		$output['errMsg'] = '';
		$output['no'] = 0;

		return $output;
	}

	/**
	 * 给某个菜单评价
	 *
	 * 对应API：personal/Ajax/点评商品等级
	 * 请求类型：post
	 * @return [type]          [description]
	 */
	public function putMenu_comment(){
		$data = array();
		$data['shop_id'] = Input::get('shop_id');
		$data['deal_id'] = Input::get('deal_id');
		$data['goods_id'] = Input::get('goods_id');
		$data['goods_level'] = Input::get('goods_level');
		$data['goods_comment'] = Input::get('goods_comment');

		Comment::insert($data);

		$output = array();
		$output['success'] = 'true';
		$output['state'] = 200;
		$output['nextSrc'] = '';
		$output['errMsg'] = '';
		$output['no'] = 0;
		return $output;
	}

}
