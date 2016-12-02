<?php


class cmfRequest {


	private $get;
	private $getBak;
	private $post;
	private $files;



	function __construct() {
		$this->get = &$_GET;
		$this->getBak = array();
		$this->post = &$_POST;
		$this->files = &$_FILES;
	}



	public function setBak() {
		$this->getBak = $this->get;
	}
	public function resetGet() {
		$this->get = $this->getBak;
	}


	// работа с $_GET
	public function isGet($n) {
		return isset($this->get[$n]);
	}
	public function getGet($n, $d=null) {
		return get($this->get, $n, $d);
	}
	public function getGetAll() {
		return $this->get;
	}
	public function setGet($n, $v) {
		$this->get[$n] = $v;
		//$this->getBak[$n] = $v;
	}
	public function unsetGet($n) {
		unset($this->get[$n]);
	}


	// работа с $_POST
	public function getPost($n, $d=null) {
		return get($this->post, $n, $d);
	}
	public function getPostAll() {
		return $this->post;
	}
	public function isPost($n) {
		return isset($this->post[$n]);
	}
	public function setPost($n, $v) {
		$this->post[$n] = $v;
	}


	// работа с $_FILES
	public function getFiles($n) {
		return get($this->files, $n);
	}


	static public function viewParam($n) {
		$uri='';
		foreach($n as $k=>$v) {
			if(!is_null($v)) $uri .= '&'.  ($k) .'='.  ($v);
        }
        return $uri;
	}

}

?>
