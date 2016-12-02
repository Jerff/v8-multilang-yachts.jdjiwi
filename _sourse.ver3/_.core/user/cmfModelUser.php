<?php

cmfLoad('user/cmfModelUserDriver');
class cmfModelUser extends cmfModelUserDriver {


	static private function getWhere() {
		return array("(`admin` IS NULL)");
	}

	public static function isUser($id) {
		return self::isUserWhere($id, self::getWhere());
	}
	static public function accesIs($id) {
		if(!self::isUser($id)) exit;
	}

 	static public function getLogin($login){
		return self::getLoginWhere($login, self::getWhere());
	}

	static public function changePassword($login, $cod){
		return self::changePasswordWhere($login, $cod, self::getWhere());
	}

}

?>