<?php


class cmfBackupTable {

	const dump1 =	0;
	const dump2 =	1;
	const page =	2;

	public static function getFile($file) {
		switch($file) {
			case self::dump1:	return cmfData .'dump/site/dump_1.sql.gz';
			case self::dump2:	return cmfData .'dump/site/dump_2.sql.gz';
			case self::page:	return cmfData .'dump/pages/dump_1.sql.gz';
		}
		exit;
	}

	public static function optimize() {
		cmfRegister::getSql()->optimize();
	}

	public static function exportDumpPages() {
        cmfDebug::sqlLogOff();
        ignore_user_abort(true);
		set_time_limit(0);

        $_table = array(db_admin_cache, db_pages_admin, db_pages_main, db_access_read, db_access_write);
		$_noData = array(db_admin_cache);
		cmfBackup::export(self::getFile(self::page), $_table, $_noData);
		ignore_user_abort(false);
		cmfDebug::addLog('Экспорт завершен');
		cmfDebug::sqlLogOn();
 	}


	public static function importDumpPages() {
		cmfDebug::sqlLogOff();
		ignore_user_abort(true);
		set_time_limit(0);

		cmfBackup::import(self::getFile(self::page));
		ignore_user_abort(false);
		cmfDebug::addLog('Импорт завершен');
		cmfDebug::sqlLogOn();
	}


	public static function exportDump() {
		cmfDebug::sqlLogOff();
		ignore_user_abort(true);
		set_time_limit(0);

		$_table = cmfRegister::getSql()->getTableList();
		unset($_table[db_admin_cache]);
		unset($_table[db_pages_admin]);
		unset($_table[db_pages_main]);
		unset($_table[db_access_read]);
		unset($_table[db_access_write]);

		$_noData = array(
			db_cache_data,
			db_cache_update,
			db_search);
		$_noData = array_combine($_noData, $_noData);

		foreach($_table as $k=>$v) {
			if(strpos($v, cmfDbPefix)!==0) {
				unset($_table[$k]);
			}
		}

		ignore_user_abort(true);
		set_time_limit(0);
		$dump1 = self::getFile(self::dump1);
		$dump2 = self::getFile(self::dump2);
		if(is_file($dump1)) {
			if(is_file($dump2)) {
				unlink($dump2);
			}
			copy($dump1, $dump2);
		}
		cmfBackup::export($dump1, $_table, $_noData);
		ignore_user_abort(false);
		cmfDebug::addLog('Экспорт завершен');
		cmfDebug::sqlLogOn();
	}

	public static function importDump() {
		cmfDebug::sqlLogOff();
		ignore_user_abort(true);
		set_time_limit(0);

		cmfBackup::import(self::getFile(self::dump1));
		ignore_user_abort(false);
		cmfDebug::addLog('Импорт завершен');
		cmfDebug::sqlLogOn();
	}

}

?>
