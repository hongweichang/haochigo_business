<?php


/**
 * 个人管理控制器
 *
 * addOrder()				添加订单
 * cancelOrder()			取消某个订单，会立刻删除
 * confirmOrder()			确认收货
 * collectGood()			收藏某个菜单
 * collectMenu()			收藏某个店铺
 * delMenu()				删除收藏的某个菜单
 * delShop()				删除收藏的某个店铺
 * getDeal_info()			获取某一个订单的信息
 * getMy_collect_goods()	获取我收藏的商品
 * getMy_store()			获取我收藏的店铺
 * getRecent_user_deal()	获取某个用户最近的订单
 * putMenu_comment()		给某个菜单进行评价
 * putMenu_speed()			给某个订单进行评价
 */

class PersonController extends Controller {

	/**
	 * 添加订单
	 *
	 * 对应API：
	 * 请求类型：POST
	 */
	public function addOrder(){
		$order = new Order;
		$order->shop_id = Input::get('shop_id');
		$order->front_user_id = Input::get('front_user_id');
		$order->ordertime = time();
		$order->total = Input::get('total');
		$order->order_menus = Input::get('menus');
		$order->total_pay = Input::get('total_pay');
		$order->score_money = Input::get('score_mon');
		$order->dispatch = Input::get('dispatch');
		$order->beta = Input::get('beta');
		$order->state = 5;
		$order->receive_address_id = Input::get('receive_address_id');

		$order->save();
	}

	/**
	 * 取消某个订单
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return [type] [description]
	 */
	public function cancelOrder(){
		$order_id = Input::get('order_id');
		Order::find($order_id)->delete();
	}

	/**
	 * 确认收货
	 *
	 * 对应API
	 * 请求类型：POST
	 * @return array 执行状态
	 */
	public function confirmOrder(){
		$order_id = Input::get('order_id');
		Order::where('id', $order_id)->update(array('state' => 1));
	}

	/**
	 * 收藏某个菜单
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return array 执行状态
	 */
	public function collectGood(){
		$collect = new CollectMenu;
		$collect->user_id = Input::get('user_id');
		$collect->menu_id = Input::get('menu_id');
		$collect->save();
	}

	/**
	 * 收藏某个店铺
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return array 执行状态
	 */
	public function collectMenu(){
		$collect = new CollectShop;
		$collect->user_id = Input::get('user_id');
		$collect->shop_id = Input::get('shop_id');
		$collect->save();
	}

	/**
	 * 删除收藏的商品
	 * @return array 执行状态
	 */
	public function delMenu(){
		$shop_id = Input::get('shop_id');		// 这个应该是不必要的，因为商品本就是唯一的
		$menu_id = Input::get('good_id');	

		CollectMenu::where('menu_id', $menu_id)->delete();
	}

	/**
	 * 删除收藏的商家
	 *
	 * 对应API：personal/删除收藏的商家
	 * 请求类型：POST
	 * @return array 执行状态
	 */
	public function delShop(){
		$shop_id = Input::get('shop_id');

		CollectShop::where('shop_id', $shop_id)->delete();
	}

	/**
	 * 获取某个用户最近的订单
	 *
	 * @param 	integer $front_uid 前台用户的ID
	 * @return 	integer $days 限制天数：天1、周7、月30、所有0
	 */
	 public function getRecent_user_deal($front_uid, $days = 7){

		// unix时间戳1天86400，1周604800，1月2629743，1年31556926

		$amount = Order::where('front_user_id', $front_uid)->count();

		$beginToday = mktime(0, 0, 0, date('m'), date('m'), date('d'), date('Y'));z
		$endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
		echo $endToday;
		echo '<br>';
		$beginLastweek = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y'));
		echo $beginLastweek;
		echo '<br>';
		echo time(); 
	}

	/**
	 * 获取某一个订单的信息
	 *
	 * 对应API/personal/个人中心/output/personal/recent_deal
	 * @param  integer $order_id 订单ID
	 * @return [type]
	 */
	public function getDeal_info($order_id){
		$data = array();

		$order = Order::find($order_id);
		$data['shop_id'] = $order->shop_id;
		$data['front_user_id'] = $order->front_user_id;
		$data['mobile'] = $order->mobile;
		$data['tel'] = $order->tel;
		$data['ordertime'] = $order->ordertime;
		$data['arrivetime'] = $order->arrivetime;
		$data['total'] = $order->total;
		$data['order_menus'] = $order->order_menus;
		$data['total_pay'] = $order->total_pay;
		$data['score_money'] = $order->score_money;
		$data['pay_method'] = $order->pay_method;
		$data['dispatch'] = $order->dispatch;
		$data['beta'] = $order->beta;
		$data['state'] = $order->state;
		$data['address'] = $order->address;
		$data['receive_name'] = $order->receive_name;

		return $data;
	}

	/**
	 * 获取我收藏的商品
	 * 
	 * 对应API：personal/我收藏的商品
	 * @return array 收藏的商品的简介
	 */
	public function getMy_collect_goods($front_uid){
		$data = array();
		$menus = array();

		$goods = Collectmenu::where('user_id', $front_uid)->lists('menu_id');

		foreach($goods as $menu_id){
			$info = array();			// 需要获取美食名称/所属餐厅/单价/人气(月售多少)

			$menu = Menu::find($menu_id);

			$info['shop_id'] = $menu->shop_id;
			$info['title'] = $menu->title;
			$info['price'] = $menu->price;
			$info['unit'] = $menu->unit;
			$info['sold_month'] = $menu->sold_month;

			array_push($menus, $info);
		}

		array_push($data, array('num' => count($data)));
		array_push($data, $menus);
		return $data;
	}

	/**
	 * 我收藏的店铺(这个商家内容与下面的不一样.. →_→)
	 *
	 * 对应API：main/blade/my_store
	 * @param  int $front_uid 用户的ID
	 * @return array  	结果数组
	 */
	public function getMy_store($front_uid){
		# 首先要获取这个用户收藏的店铺，最多选取5个
		$stores = Collectshop::where('uid', $front_uid)->orderBy('uptime', 'desc')->take(5)->lists('shop_id');

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
	 * 给某个菜单评价
	 *
	 * 对应API：personal/Ajax/点评商品等级
	 * 请求类型：POST
	 * @return [type]          [description]
	 */
	public function putMenu_comment(){
		$data = array();
		$data['shop_id'] = Input::get('shop_id');
		$data['deal_id'] = Input::get('deal_id');
		$data['goods_id'] = Input::get('goods_id');
		$data['goods_level'] = Input::get('goods_level');
		$data['goods_comment'] = Input::get('goods_comment');

		CommentMenu::insert($data);

		$output = array();
		$output['success'] = 'true';
		$output['state'] = 200;
		$output['nextSrc'] = '';
		$output['errMsg'] = '';
		$output['no'] = 0;
		return $output;
	}

	/**
	 * 给某个订单做评价
	 *
	 * 对应API：personal/点评送餐速度
	 * 请求类型：POST
	 * @return array 执行状态
	 */
	public function putMenu_speed(){
		$data = array();
		$data['front_uid'] = Input::get('');			// 从session获取
		$data['shop_id'] = Input::get('shop_id');
		$data['order_id'] = Input::get('deal_id');
		$data['deal_speed'] = Input::get('deal_speed');
		$data['comment'] = Input::get('deal_satisfied_comment');
		$data['value'] = Input::get('deal_satisfied');

		CommentOrder::insert($data);
	}

}
