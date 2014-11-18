<?php

/**
 * 店铺管理控制器
 *
 * get_shop_list()	获取餐厅列表
 * get_comment()	获取商家的评论统计信息
 */

class ShopController extends BaseController {

	/**
	 * 获取某个店铺的菜单
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return array 该店铺的菜单
	 */
	public function getMenus(){

	}

	/**
	 * 获取餐厅列表(目测饿了吗是根据地址来推荐的餐厅)
	 * 两种情况：推荐餐厅和更多餐厅
	 *
	 * 对应API：main/blade/my_store  和 main/blade/more_shop
	 *
	 * @param  integer $uid 用户id
	 * @param  integer $x 经度
	 * @param  integer $y 维度
	 * @return array 结果数组
	 */
	public function get_shop_list($uid = 2, $x = 0 , $y = 0){		
		$result = array();
		$data = array();

		# 首先获取所有的活动
		$activity = Activity::all()->toArray();
		
#TODO 然后获取推荐的店铺(至于到底返回多少的数量，这个只能根据经纬度的那个算法了)
		$shops = array();
		//int $i = 0;
		$all_shops = Shop::all();
		
		foreach($all_shops as $shop){
			$onestore = array();

			$onestore['support_activity'] = explode(',', $shop->support_activity);		// 所有支持的活动id
			$onestore['isHot'] = $shop->is_hot?true:false;								// 是否是热门餐厅
			$onestore['isOnline'] = $shop->is_online?true:false;						// 是否营业		
			$onestore['isSupportPay'] = in_array('1', explode(',', $shop->pay_method));	// 是否支持在线支付
			$onestore['shop_id'] = $shop->id;											// 商家id
			$onestore['place_id'] = $shop->x;									// -------------------位置经纬度和位置id后期再改数据库
			$onestore['shop_url'] = 'shop/'.$shop->id;									// 点击跳转到相应商家
			$onestore['shop_logo'] = $shop->pic;		  								// 商家的logo图片地址
			$onestore['deliver_time'] = $shop->interval;								// 送货时间间隔
			$onestore['deliver_start'] = $shop->begin_time;								// 送货开始时间
			$onestore['shop_name'] = $shop->name;										// 商家名称
			$onestore['shop_level'] = $shop->level;										// 五分制
			$onestore['shop_announce'] = $shop->announcement;							// 商家公告
			$onestore['deliver_start_statement'] = $shop->deliver_start_statement;		// 起送价描述
			$onestore['shop_address'] = $shop->address;									// 商家地址
			$onestore['is_opening'] = $shop->state;										// 0是正在营业，1是打烊了，2是太忙了
			$onestore['close_msg'] = $shop->close_msg;									// 关门信息
			$onestore['business_hours'] = $shop->operation_time;						// 营业时间
			$onestore['shop_summary'] = $shop->intro;									// 商家简介
			$onestore['order_count'] = $shop->sold_num;									// 订单数量
			$onestore['is_collected'] = in_array($shop->id, Collectshop::where('uid', $uid)->lists('shop_id'));	// 是否被收藏了
			$onestore['additions'] = array();													// 额外的内容

			array_push($shops, $onestore);
		}	
		

		$data['activity'] = $activity;
		$data['shops'] = $shops;
		$result['data'] = $data;
		return $result;
	}

	/**
	 * 获取商家的评论统计信息，在店铺页面指着店铺名称时所显示的那些信息、
	 *
	 * 对应API：shop/blace/output/data
	 * @param integer $sid 店铺ID
	 * @return array 包含那些信息的ajax数据
	 */
	public function get_comment($sid){
		$result = array();
		$s_info = Shop::find($sid);

		$result['shop_id'] = $s_info->id;
		$result['place_id'] = '123';	// ------------------
		$result['shop_logo'] = $s_info->pic;
		$result['shop_name'] = $s_info->name;
		$result['shop_type'] = $s_info->type;
		// 计算各种比例是有一点麻烦。。。
		// shop表的id join 菜单表的id和shop_id join 评论表的menu_id和value，然后计算value在各个段的平均值。。。
		
		$hehe = Comment::join('menu', 'comment.menu_id', '=', 'menu.id')
					->select('comment.value')
					->where('menu.shop_id', '=', $sid);
		$total = count($hehe->get());

		// 由于没有设置查询缓存，也只能这样子计算了
		$min_5 = count($hehe->whereBetween('value', array(0.0, 5.0))->get());
		$min_4 = count($hehe->whereBetween('value', array(0.0, 4.0))->get());
		$min_3 = count($hehe->whereBetween('value', array(0.0, 3.0))->get());
		$min_2 = count($hehe->whereBetween('value', array(0.0, 2.0))->get());
		$min_1 = count($hehe->whereBetween('value', array(0.0, 1.0))->get());

		$percent_1 = $min_1 / $total;
		$percent_2 = ($min_2 - $min_1) / $total;
		$percent_3 = ($min_3 - $min_2) / $total;
		$percent_4 = ($min_4 - $min_3) / $total;
		$percent_5 = ($min_5 - $min_4) / $total;

		$result['shop_level'] = '';
		$result['shop_total'] = '';
		$result['comment_count'] = '';
		$result['shop_statement'] = '';
		$result['shop_time'] = '';
		$result['shop_address'] = '';
		$result['deliver_begin'] = '';
		$result['shop_distance'] = '';
		$result['price_begin'] = '';
		$result['is_collected'] = '';
		
		return '------------------------------';
		//return $result;
	}

}