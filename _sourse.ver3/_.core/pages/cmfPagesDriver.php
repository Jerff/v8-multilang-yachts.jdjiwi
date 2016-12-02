<?php


class cmfPagesDriver {

	private static $main = null;
	private static $item = null;
	private static $param = null;

	private static $page = null;
	private static $templates = null;
	private static $base = null;



	// ��������� & ����������� ����� ������� ��������
	public static function setMain($p) {
		self::$main = $p;
		self::$item = $p;
	}
	public static function getMain() {
		return self::$main;
	}
	public static function isMain($p=null) {
		return self::$main === ($p ? $p : self::$item);
	}




	// ��������� & ����������� ����� ������� ��������
	public static function setItem($p) {
		self::$item = $p;
	}
	public static function getItem() {
		return self::$item;
	}



	// ��������� & ����������� ��������� ����
	public static function setParam($p) {
		if($p)
		foreach($p as $k=>$v) {
			//$p[$k] = str_replace(array("'", '"'), '', $v);
		}
		self::$param = $p;
	}
	public static function getParam($n) {
		return get(self::$param, $n-1);
	}
	public static function &getParamAll() {
		return self::$param;
	}



	// ��������� & ����������� ������ �������
	public static function setPage($p) {
		self::$page = $p;
	}
	public static function getPage($n) {
		return get(self::$page, $n);
	}
	public static function getPageFlag($n, $f) {
		return get2(self::$page, $n, $f);
	}
	public static function getPageBase($n) {
		return self::getBase(self::$page[$n]['part']);
	}



	// ��������� & ����������� �������
	public static function setBase($b) {
		self::$base = $b;
	}
	public static function getBase($n) {
		return isset(self::$base[$n]) ? self::$base[$n] : null;
	}



	public static function setTemplates($t) {
		self::$templates = $t;
	}
	public static function getTemplates($n) {
		return isset(self::$templates[$n]) ? self::$templates[$n] : null;
	}



	public static function getUrl() {
		return 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	public static function getUrl2() {
		return 'http://'. $_SERVER['HTTP_HOST'];
	}
	public static function getUri() {
		return $_SERVER['REQUEST_URI'];
	}

	// ������� ��� ����
	public static function getPath() {
		return parse_url(self::getUrl(), PHP_URL_PATH);
	}
/*	public static function getPathDomen() {
		static $url = null;
		if($url) return $url;
		return $url = domen . parse_url(self::getUrl(), PHP_URL_PATH);
	}
*/
/*

	public static function end() {
		self::$main = null;
		self::$item = null;
		self::$param = null;

		self::$seo = null;

		self::$patterns = null;
		self::$base = null;
	}*/

}

?>
