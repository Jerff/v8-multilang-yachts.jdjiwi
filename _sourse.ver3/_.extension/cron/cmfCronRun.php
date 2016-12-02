<?php


class cmfCronRun {

	const file = '.cron/cron.run';

	static private function getFile() {
		return cmfSourse . self::file;
	}

	static public function run() {
		if(cmfCommand::is('$isCron')) {
			file_put_contents(self::getFile(), time());
		}
	}

	static public function free() {
		if(cmfCommand::is('$isCron')) {
			if(file_exists(self::getFile())) {
				unlink(self::getFile());
			}
		}
	}

	static public function isRun() {
        if(file_exists(self::getFile())) {
			if((file_get_contents(self::getFile())+60*5)>time()) {
				return true;
			}
        }
        return false;
	}


	static public function runModul($name, $id) {
		cmfRegister::getSql()->add(db_sys_cron, array('status'=>'start', 'date'=>date('Y-m-d H:i:s')), $id);
		self::run();
		cmfCronConfig::runModul($name);
		self::free();
		cmfRegister::getSql()->add(db_sys_cron, array('status'=>'end', 'date'=>date('Y-m-d H:i:s')), $id);
		exit;
	}

}

?>