<?php


class cmfCacheUser {

    const name = '$cacheId';
	static private $config = array();

	static public function getCacheId() {
	    cmfCookie::get(self::name);
	}
	static private function init() {
		$d = self::get('$discountId');
	    if($d) {
	    	$cacheId = $d;
	    } else {
	    	$cacheId = '';
	    }
	    if($cacheId) {
    	    cmfCookie::set(self::name, $cacheId);
	    } else {
	        cmfCookie::del(self::name);
	    }
	}
	static private function parse() {
		$cacheId = cmfCookie::get(self::name);
	    if($cacheId) {
	    	$d = $cacheId;
	    } else {
	    	$d = 1;
	    }
	    self::set('$discountId', $d);
	}


	static private function get($n, $default=null) {
		if(!isset(self::$config[$n])) {
    		self::parse();
        }
		return get(self::$config, $n, $default);
	}
	static private function is($n) {
		return isset(self::$config[$n]);
	}
	static private function set($n, $v) {
		self::$config[$n] = $v;
	}


	static public function getDiscount() {
		return self::get('$discountId', 1);
	}
	static public function setDiscount($d) {
		self::set('$discountId', $d);
		self::init();
	}

	static public function getUserPay() {
		return self::get('$userPay');
	}
	static public function setUserPay($d) {
		self::set('$userPay', $d);
	}

}

?>