<?php


abstract class cmfUserDriver {

	private static $user = null;

	private		$name = null;
	private		$id = 0;

	private		$data = null;
	private		$security = null;

	private		$db = null;
	private		$ses = null;
	private		$ban = null;



	public function __construct(){
		$this->setDb(db_user);
		$this->setSes(db_user_ses);

		$this->sessionGet();
		$this->is();
	}


	public function free()  {
		unset($this->security, $this->db, $this->ses, $this->ban);
	}

	public function disable(){
		$this->sessionDel();
		if($this->isSes() and $this->getId()) {
			cmfRegister::getSql()->del($this->getSes(), $this->getId());
		}
	}


	protected function setId($id) {
		$this->id = (int)$id;
	}
	public function getId() {
		return $this->id;
	}


	protected function loadData(&$row) {
		$row = array_merge($row, $this->getDataParam());
	}
	protected function setData($data) {
		$this->data = $data;
	}
	protected function setDataId($k, $data) {
		$this->data[$k] = $data;
	}
	protected function getData() {
		return $this->data;
	}


	private function setSecurity($security) {
		$this->security = $security;
	}
	private function getSecurity() {
		return $this->security;
	}


	abstract protected function getFields();

	protected function getWhere() {
		return array(1);
	}


	protected function setName($name) {
		$this->name = $name;
	}
	protected function getName() {
		return $this->name;
	}


	protected function setDb($db) {
		$this->db = $db;
	}
	protected function getDb() {
		return $this->db;
	}


	protected function isSes() {
		return $this->ses!==null;
	}
	protected function setSes($ses) {
		$this->ses = $ses;
	}
	protected function getSes() {
		return $this->ses;
	}


	protected function setBan($ban) {
		$this->ban = $ban;
	}
	protected function getBan() {
		return $this->ban;
	}



	protected function sessionSet($id, $data, $security) {
		$this->setId($id);
		$this->sessionNext();
		$security['date'] = time();
		cmfSession::set($this->getName(), array(	'id'=>$id,
													'data'=>$data,
													'security'=>$security));
		$this->cookieSetData();
	}

	protected function sessionUpdate($data) {
		cmfSession::set($this->getName(), 'data', $data);
		$this->cookieSetData();
	}

	protected function sessionNext() {
		cmfCookie::set($this->getName(), $this->getId(), 12);
		cmfSession::set($this->getName(), 'security', 'date', time());
	}

	protected function sessionDel() {
		$this->setId(0);
		$name = $this->getName();
		cmfCookie::del($name);
		cmfSession::del($name);
	}

	protected function sessionGet() {
		$name = $this->getName();
		if(!$id = (int)cmfCookie::get($name)) return;
		$this->setId($id);


		if(!$ses = cmfSession::get($name)) return;
		if(get2($ses, 'data', 'debugError')==='yes') return;
		if(isset($ses['id']) and $ses['id']==$id) {
			if(isset($ses['data'])) {
				$this->setData($ses['data']);
			}
			if(isset($ses['security'])) {
				$this->setSecurity($ses['security']);
			}
		}
	}

	protected function cookieSetData() {
	}

	public function resetData() {
		$row = cmfRegister::getSql()->placeholder("SELECT `isIp`, ?fields FROM ?t WHERE id=? AND `visible`='yes' AND `register`='yes'", $this->getFields(), $this->getDb(), $this->getId())
									->fetchAssoc();
		if(!$row) {
			$this->sessionDel();
			return false;
		}
		$this->loadData($row);
		$this->setData($row);
		$this->sessionUpdate($row);
	}

	public function is() {
		if(!$this->getId()) return false;
		static $is = null;
		if($is===null) {
			$is = $this->userIs();
		}
		return $is;
	}

	private function userIs() {
		$security = $this->getSecurity();

		$id = $this->getId();
		if($security) {
			if($security['ip']!==cmfGetIpInt() or $security['proxy']!==cmfGetProxyInt()) {
				$this->sessionDel();
				return false;
			}
			if(($security['date']+60)>time()) return true;

			$this->sessionNext();
			if($this->isSes()) {
				cmfRegister::getSql()->add($this->getSes(), array('sesDate'=>date('Y-m-d H:i:s')), $id);
			}
			return true;
		}

		$sql = cmfRegister::getSql();
		if($this->isSes()) {
			$row = $sql->placeholder("SELECT 1 FROM ?t WHERE `id`=? AND IF(`isIp`='yes', `sesIp`=? AND `sesProxy`=?, 1)", $this->getSes(), $id, cmfGetIpInt(), cmfGetProxyInt())
							->numRows();
			if(!$row) {
				$this->sessionDel();
				return false;
			}
		}

		$row = $sql->placeholder("SELECT `isIp`, ?fields FROM ?t WHERE id=? AND `visible`='yes' AND `register`='yes'", $this->getFields(), $this->getDb(), $id)
						->fetchAssoc();
		if(!$row) {
			$this->sessionDel();
			return false;
		}
		$this->loadData($row);
		$this->setData($row);
		$this->userSelectEnter($row);

		if($this->isSes()) {
			$sql->replace($this->getSes(), array('id'=>$id, 'isIp'=>$row['isIp'], 'sesIp'=>cmfGetIpInt(), 'sesProxy'=>cmfGetProxyInt(), 'sesDate'=>date('Y-m-d H:i:s')));
		}
		$security = array('ip'=>cmfGetIpInt(), 'ip'=>cmfGetIpInt(), 'proxy'=>cmfGetProxyInt());
		$this->sessionSet($id, $row, $security);

		return true;
	}



	static public function hash($password) {
        return sha1($password . cmfSalt);
	}

	public function userSelect($login, $password){
		$password = self::hash($password);

		$sql = cmfRegister::getSql();
		$row = $sql->placeholder("SELECT `isIp`, ?fields FROM ?t WHERE ?w AND `login`=? AND `password`=? AND visible='yes' AND register='yes' AND UNIX_TIMESTAMP(banDate)<?", $this->getFields(), $this->getDb(), $this->getWhere(), $login, $password, time())
						->fetchAssoc();
		if(!$row) {
			$this->setErrorLogin($login);
			return false;
		}
		$id = $row['id'];
		$this->setId($id);
		$this->loadData($row);
		$this->setData($row);
        $this->userSelectEnter($row);

		$sql->add($this->getDb(), array('banCount'=>0), $id);
		if($this->isSes()) {
			$sql->replace($this->getSes(), array('id'=>$id, 'isIp'=>$row['isIp'], 'sesIp'=>cmfGetIpInt(), 'sesProxy'=>cmfGetProxyInt(), 'sesDate'=>date('Y-m-d H:i:s')));
		}
		$security = array('ip'=>cmfGetIpInt(), 'proxy'=>cmfGetProxyInt());
		$this->sessionSet($id, $row, $security);
		return true;
	}

	protected function userSelectEnter($data) {
	}



	public function get($v) {
		return get($this->data, $v);
	}
	public function __get($value) {
		if(isset($this->data[$value])) return $this->data[$value];
		if($value==='all') return $this->data;
		return null;
	}



	protected function setErrorLogin($login) {
		//$this->disable();

		$ban = $this->getBan();
		if(!$ban) return;

		$sql = cmfRegister::getSql();
		$row = $sql->placeholder("SELECT `banCount` FROM ?t WHERE `login`=? AND visible='yes' AND register='yes' AND UNIX_TIMESTAMP(banDate)<?", $this->getDb(), $login, time())
						->fetchAssoc();
		if($row) {
			$error = $row['error_count']+1;
			$time = time();
			if($error>10) {

				if($error<15) $time = time() + 60*($error-10);
				else if($error<20 ) $time = time() + 2*60*($error-10);
				else if($error<30 ) $time = time() + 3*60*($error-10);
				else $time = time() + 4*60*($error-10);
				if($error>=20) {
					$data = array(	'date'=>date('d.m.y H:i:s'),
									'user'=>$login,
									'ip'=>cmfGetIpInt(),
									'proxy'=>cmfGetProxyInt());

					//system_send_mail($this->ban_email,$ban_data);
				}
			}

			$sql->placeholder("UPDATE ?t SET ?% WHERE ?w", $this->getDb(), array('banCount'=>$error, 'banDate'=>date('Y-m-d H:i:s',$time)), array('login'=>$login));
		}
	}


	// параметры
	protected function getDataParam() {
		return array();
	}

	public function setDataParam($send, $id=null) {
	}


}

?>