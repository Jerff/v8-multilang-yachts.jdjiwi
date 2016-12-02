<?php

// автозавгрузчик классов
class cmfAdminAutoload {

	const path = '_.config|_.core/|_.extension/|_.plugin/|_library';

	static private function getCache() {
		return cmfCacheAdmin::get('__autoload');
	}
	static private function setCache(&$v) {
		cmfCacheAdmin::set('__autoload', $v, 'load');
	}
	static private function delete() {
		cmfCacheAdmin::delete('__autoload');
	}

	static public function autoload($name) {
        static $_class = null;
        if(!$_class) $_class=self::getCache();
        if(!isset($_class[$name]) or !is_file($_class[$name])) {
	        $_class = array();
	        foreach(explode('|', self::path) as $dir) {
                self::getFile(cmfSourse .$dir, $_class);
	        }
	        self::setCache($_class);
        }
        if(isset($_class[$name])) {
        	require_once($_class[$name]);
        } else {
        	pre($name);
        }
	}

	static public function getFile($dir, &$_class) {
        if(!is_dir($dir)) return;
		foreach(scandir($dir) as $file) {
			if($file{0}==='.') continue;
			if(is_dir($dir . $file)) {
				self::getFile($dir . $file .'/', $_class);
			}
			if(preg_match('~cmf[a-z]+\.php~iS', $file)) {
                $_class[substr($file, 0, -4)] = $dir . $file;
			}
		}
	}

}

?>