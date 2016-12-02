<?php


class cmfCacheAdmin {

	static private function getResurse() {
		return cmfRegister::getSql();
	}

	//очистка кеша
	static public function clear() {
		cmfRegister::getSql()->truncate(db_admin_cache);
	}

    //функции хешировани€
	static protected function hash($n) {
		return cmfCrc32(cmfDomen . $n);
	}
	static protected function hash2($n) {
		return sha1(cmfDomen . $n);
	}

	// преобразуем теги дл€ хранени€ в базе
	static private function reformTagSql($tag) {
		return $tag ? '['. str_replace(',', '][', $tag) .']' : '';
	}

	// кеширование данных
	// записываем в кеш
	static public function set($n, $v, $tag=null, $time=30) {
		self::getResurse()->replace(db_admin_cache, array('id'=>self::hash($n), 'name'=>self::hash2($n), 'tag'=>self::reformTagSql($tag), 'content'=>serialize($v), 'time'=>time()+60*$time));
	}

	// читаем из кеша
	static public function get($n) {
		$res = self::getResurse()->placeholder("SELECT name, content FROM ?t WHERE id=? AND `time`>=?", db_admin_cache, self::hash($n), time());
		$hash = self::hash2($n);
		while(list($n, $v) = $res->fetchRow()) {
            if($hash===$n) break;
		}
		$res->free();
		return $v ? unserialize($v) : false;
	}

	// удал€ем из кеша
	static public function delete($n) {
		self::getResurse()->del(db_admin_cache, array('id'=>self::hash($n), 'AND', 'name'=>self::hash2($n)));
	}

	static public function deleteTag($n) {
		$where = '';
		foreach(explode(',', $n) as $k) {
			$where .= ($where ? ' OR ' : '') .  "`tag` LIKE ". self::getResurse()->quote("%[{$k}]%") ."";
		}
		self::getResurse()->query("DELETE FROM ". db_admin_cache ." WHERE ". $where);
	}

	// кеш меню
	static public function setMenu($n, &$v) {
		$name = cmfDomen . cmfPages::getMain() . cmfPages::getItem() . $n . cmfRegister::getAdmin()->getGroupString();
		self::set($name, $v, 'menu');
	}

	static public function getMenu($n) {
		$name = cmfDomen . cmfPages::getMain() . cmfPages::getItem() . $n . cmfRegister::getAdmin()->getGroupString();
		self::get($name);

	}

}

?>