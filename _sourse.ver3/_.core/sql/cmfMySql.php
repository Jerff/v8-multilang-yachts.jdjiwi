<?php

cmfLoad('sql/cmfSqlDriver');
class cmfMySql extends cmfSqlDriver {


	function __construct() {
		$host = cmfMysqlHost;
		$db = cmfMysqDb;
		$dsn = "mysql:dbname={$db};host={$host}";
		$res = new cmfPDO($dsn, cmfMysqUser, cmfMysqPassword);
		$this->set($res);
	}

}

?>
