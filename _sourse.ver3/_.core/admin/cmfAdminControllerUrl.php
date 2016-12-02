<?php


class cmfAdminControllerUrl {

	static private function getRequest() {
		return cmfRegister::getRequest();
	}

	static public function requestUri($opt=null) {
		$get = self::getRequest()->getGetAll();
		if(!is_null($opt))
			 $get = array_merge($get, (array)$opt);

		$uri='';
		reset($get);
		while(list($k, $v) = each($get))
			if(!is_null($v)) $uri .= '&'. urlencode($k) .'='. urlencode($v);
		return $uri;
	}

}

?>
