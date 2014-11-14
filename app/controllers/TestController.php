<?php

class TestController extends BaseController {

	public function show()
	{
		$shop = Shop::all();
		return $shop;
	}

}
