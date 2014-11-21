<?php

/**
 * 店铺管理控制器
 *
 * addShop()		添加某个店铺
 * addMenu()		添加菜单
 * getClassify()	获取某个店铺菜单的分类
 * getComment()		获取商家的评论统计信息
 * getMenus()		获取某个店铺的菜单
 * getMenu()		获取某个菜单的详情
 * getOrder()		获取某个订单的详情
 * getOrderlist()	获取某个店铺指定时间的订单列表
 * getShop_list()	获取餐厅列表
 * modifyMenu()		修改某个菜单
 * modifyInfo()		修改店铺的相关信息
 * modifyOrder()	修改订单状态，如已经配送完成等
 */

class ShopController extends BaseController {

	/**
	 * 修改订单状态，如已经配送完成等，这里是配送完成，不是用户确认收获
	 * @return [type] [description]
	 */
	public function modifyOrder(){
		$order_id = Input::get('order_id');
		Order::where('id', $order_id)->update(array('state' => 1));
	}

	/**
	 * 修改店铺的相关信息
	 * @return array 执行状态
	 */
	public function modifyInfo(){
		$shop_id = Input::get('shop_id');
		$data = Input::get('data');

		$shop = Shop::where('id', $menu_id)->update($data);
	}

	/**
	 * 修改某个菜单
	 *
	 * 对应API
	 * 请求类型：POST
	 * @return array 执行状态
	 */
	public function modifyMenu(){
		$menu_id = Input::get('menu_id');
		$data = Input::get('data');

		$menu = Menu::where('id', $menu_id)->update($data);


	}

	/**
	 * 添加菜单
	 *
	 * 对应API
	 * 请求类型：POST
	 * @return array 执行状态
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
	 * 获取某个菜单的详情
	 *
	 * 对应API
	 * 请求类型：POST
	 * @return array 执行状态
	 */
	public function getMenu(){
		$menu_id = Input::get('menu_id');
		return Menu::find($menu_id);
	}

	/**
	 * 获取某个订单的详情
	 * @return [type] [description]
	 */
	public function getOrder(){
		$order_id = Input::get('order_id');

		return Order::find($order_id);
	}

	/**
	 * 获取某个店铺指定时间的订单列表
	 * @return [type] [description]
	 */
	public function getOrderlist(){
		$shop_id = Input::get('shop_id');

		$amount = Order::where('shop_id', $shop_id)->count();

		$beginToday = mktime(0, 0, 0, date('m'), date('m'), date('d'), date('Y'));
		$endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
		echo $endToday;
		echo '<br>';
		$beginLastweek = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y'));
		echo $beginLastweek;
		echo '<br>';
		echo time();

	}

	/**
	 * 添加店铺的功能
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return array 执行状态信息
	 */
	public function addShop(){
		$shop = new Shop;

		$shop->b_uid = Input::get('b_uid');	// 这是商业用户的id，不是前端给的，从session里面获取

		$shop->name = Input::get('name');

		$shop->addtime = time();

		$shop->intro = Input::get('intro');
		$shop->linkname = Input::get('linkname');
        //一般电话需要做格式校验
		$shop->linktel = Input::get('linktel');
		$shop->tel = Input::get('tel');

		$shop->address = Input::get('address');
        //比如这个价格，只能是数字那么需要加判断
		$shop->least_price = Input::get('least_price');
		$shop->dispatch_price = Input::get('dispatch_price');
        //这个店铺开启状态值，你觉得会让用户在添加店铺的时候设置么？
		$shop->state = Input::get('state');
        //图片不是这样获取的，你自己查一下PHP是怎么处理前端上传的图片的
		$shop->pic = Input::get('pic');

        //这种状态值形式的数据，前端给过来的时候，是不会和我们数据库里的形式一样的，你要知道，前端所有代码都是别人能看的，我们能把我们数据库的存储方式放在前端么？
		$shop->ticket = Input::get('ticket');
        //同上
        $shop->pay_method = Input::get('pay_method');
        //微信id，也最好做校验，格式
		$shop->weixin = Input::get('weixin');

        //这里你要看前端是怎么填写，选择还是输入框
		$shop->interval = Input::get('interval');
        //这里肯定前端只会传两个时间给你，你需要自己拼接字符串啊
		$shop->operation_time = Input::get('operation_time');
        //同上
		$shop->type = Input::get('type');


		$shop->reserve = Input::get('reserve');
		$shop->support_activity = Input::get('support_activity');
		$shop->begin_time = Input::get('begin_time');
		$shop->announcement = Input::get('announcement');
		$shop->deliver_start_statement = Input::get('deliver_start_statement');
		$shop->additions = Input::get('additions');

		$shop->save();
	}

	/**
	 * 获取某个店铺菜单的分类
	 *
	 * 对应API：
	 * 请求类型：POST or GET
	 * @param integer $shop_id=0 根据是否传入$shop_id来确定是否是POST请求
	 * @return array 分类id和分类名称组成的键值对
	 */
	public function getClassify($shop_id = 0){
		if($shop_id == 0)
			$shop_id = Input::get('shop_id');

		return Menugroup::where('shop_id', $shop_id)->lists('name', 'id');
	}

	/**
	 * 获取某个店铺的菜单
	 *
	 * 对应API：
	 * 请求类型：POST
	 * @return array 该店铺的菜单（分了类的）
	 */
	public function getMenus(){

		$shop_id = 1;

		$result = array();							// 最终结果
		$classify = self::getClassify($shop_id);	// 获取分类的数组

		$result['class_num'] = count($classify);	// 分类数量
		$menus = array();

		foreach($classify as $class_id => $class_name){
			$one_class = array();
			$one_class['name'] = $class_name;
			$one_class['menu'] = Menu::where('group_id', $class_id)->select('id', 'title', 'price', 'pic', 'sold_month', 'state', 'comment_score', 'comment_num')->get();

			array_push($menus, $one_class);
		}
		$result['menus'] = $menus;
		return $result;
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
	public function getShop_list($uid = 2, $x = 0 , $y = 0){		
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
	public function getComment($sid){
		$result = array();
		$s_info = Shop::find($sid);

		$result['shop_id'] = $s_info->id;
		$result['place_id'] = '123';	// ------------------
		$result['shop_logo'] = $s_info->pic;
		$result['shop_name'] = $s_info->name;
		$result['shop_type'] = $s_info->type;
		// 计算各种比例是有一点麻烦。。。
		// shop表的id join 菜单表的id和shop_id join 评论表的menu_id和value，然后计算value在各个段的平均值。。。
		
		$hehe = CommentMenu::join('menu', 'comment.menu_id', '=', 'menu.id')
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



    //店铺坐标geohash设置范例
    public function geoHashSet(){
        $geohash = new Geohash();
        /*
         **此处参数分别对应，经度，纬度，商铺id，商业用户id。修改时，后面两个id可以为空,这里的数据格式我在model里面会验证
         **返回值为数组，status为ok则为成功，msg对应相应反馈信息
         **你后面如果有开发后端内部API的需求，返回值格式建议与我统一
        */
        $set = $geohash->geohashSet(39.98123662, 116.30683690,4,4);

        var_dump($set);
    }


    //根据坐标查询附近店铺范例
    public function geoHashGet(){
        $geohash = new Geohash();

        /*
         **此处参数对应经纬度
         **返回值为数组，status标识同上
         **data对应查到的数据，若不为空，data数组内，geohash对应geohash表的坐标及geohash信息，shop对应shop表里面的店铺数据
         */
        $get = $geohash->geohashGet(39.98123662, 116.30683690);

        var_dump($get);
    }

}