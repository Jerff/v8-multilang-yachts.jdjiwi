<?php


class cmfCronUserActivateClear {

	static public function run() {
		cmfCronRun::run();
		$date = date('Y-m-d H:i:s');
		$res = cmfRegister::getSql()->placeholder("SELECT GROUP_CONCAT(id) FROM ?t WHERE visible='no' AND register='no' AND DATE_ADD(registerDate, INTERVAL 5 DAY)<NOW()", db_user)
										->fetchRow(0);
        if($res) {
			cmfRegister::getSql()->placeholder("DELETE FROM ?t WHERE id IN(?s)", db_user, $res);
			cmfRegister::getSql()->placeholder("DELETE FROM ?t WHERE id IN(?s)", db_user_data, $res);
			cmfRegister::getSql()->placeholder("DELETE FROM ?t WHERE user IN(?s)", db_user_adress, $res);
        }
		cmfCronRun::free();
	}

}

?>