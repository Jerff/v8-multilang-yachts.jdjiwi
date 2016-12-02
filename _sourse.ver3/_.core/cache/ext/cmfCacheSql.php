<?php

cmfLoad('cache/driver/cmfCacheDriverSql');
class cmfCacheSql extends cmfCacheDriverSql {

	function __construct() {
		$this->setResurse(cmfRegister::getSql());
	}

}

?>