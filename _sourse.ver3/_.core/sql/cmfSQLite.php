<?php

cmfLoad('sql/cmfSqlDriver');
class cmfSQLite extends cmfSqlDriver {


	function __construct($db, $user, $password) {
		$dsn = "sqlite:". $db;
		$res = new cmfPDO($dsn, $user, $password);
		$this->set($res);
	}

}

?>
