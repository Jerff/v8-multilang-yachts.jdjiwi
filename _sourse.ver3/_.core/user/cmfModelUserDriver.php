<?php


class cmfModelUserDriver {


	static public function userUnban($id) {
		$send = array(	'banCount'=>0,
						'banDate'=>date("Y-m-d H:i:s"));
		cmfRegister::getSql()->add(db_user, $send, $id);
	}

	static public function userExit($id) {
		$send = array(	'sesIp'=>0,
						'sesProxy'=>0);
		cmfRegister::getSql()->add(db_user_ses, $send, $id);
	}

	static public function isNew($login, $id=0) {
		$login2 = cmfString::translate($login);
		return !cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE `id`!=? AND (`login`=? OR `login2`=?) LIMIT 0, 1", db_user, $id, $login, $login2)
										->numRows();
	}

	static public function isPassword($password, $id) {
		return cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE id=? AND password=?", db_user, $id, cmfUserDriver::hash($password))
										->numRows();
	}

 	static protected function getLoginWhere($login, $where){
		return cmfRegister::getSql()->placeholder("SELECT id, name FROM ?t WHERE ?w AND `login`=? AND visible='yes' AND register='yes'", db_user, $where, $login)
									->fetchAssoc();
	}

	static private function generatePassword($length=12){
		$keychars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_:;";
		$randkey = "";
		$max=strlen($keychars)-1;
		for($i=0; $i<=$length; $i++)
			$randkey .= $keychars{rand(0,$max)};
		return $randkey;
	}
	static protected function changePasswordWhere($login, $cod, $where){
		$row = cmfRegister::getSql()->placeholder("SELECT id, name FROM ?t WHERE ?w AND `login`=? AND registerCommand='changePassword' AND registerCod=? AND visible='yes' AND register='yes'", db_user, $where, $login, $cod)
										->fetchAssoc();
		if($row) {
			$data = array('registerCommand'=>'', 'password'=>self::generatePassword());
			self::save($data, $row['id']);
			$row['password'] = $data['password'];
			return $row;
		} else {
			return false;
		}
	}

	static protected function isUserWhere($id, $where) {
		return cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE `id`=? AND ?w", db_user, $id, $where)
									->numRows();
	}

	static public function save($send, $id=0) {
		if($id and (isset($send['login']) or isset($send['password']) or isset($send['admin']))) {
			$ses = array(	'sesIp'=>0,
							'sesProxy'=>0);
			cmfRegister::getSql()->add(db_user_ses, $ses, $id);
		}
		if(isset($send['login'])) {
			$send['login2'] = cmfString::translate($send['login']);
		}
		if(isset($send['password'])) {
			$send['password'] = cmfUserDriver::hash($send['password']);
		}
		if(!$id) {
			$send['registerDate'] = date('Y-m-d H:i:s');
		}
        return cmfRegister::getSql()->add(db_user, $send, $id);
	}

}

?>