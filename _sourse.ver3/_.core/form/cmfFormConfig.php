<?php


class cmfFormConfig {

	static private $config = null;

	static private function start() {
		if(!self::$config) return ;
		self::$config = array(
		'Заполните обязательные поля!'=>'Заполните обязательные поля!',
		''=>'',
		);
	}

	static public function get($n) {
		self::start();
		return get(self::$config, $n, $n);
	}

}
?>
