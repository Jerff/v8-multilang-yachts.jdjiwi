<?php


class cmfData {

	const noData = '<i>отсутсвуют даные</i>';

	private $data = array();


	function __construct($v=null) {
		$this->data = $v;
	}


	public function lang($n, $default=null) {
		return get($this->data, $n, get2($this->data, 'lang', $n, $default ? $default : self::noData));
	}

	public function getLang($n) {
		return get($this->data, $n, get2($this->data, 'lang', $n));
	}

	public function __get($n) {
		return get($this->data, $n, self::noData);
	}
	public function __set($n, $v) {
		$this->data[$n] = $v;
	}

	public function get($n) {
		return get($this->data, $n);
	}
	public function getInt($n) {
		return (int)get($this->data, $n);
	}
	public function html($n) {
		return htmlspecialchars($this->$n);
	}


	public function is($n) {
		return isset($this->data[$n]);
	}

}

?>