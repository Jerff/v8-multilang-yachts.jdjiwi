<?php


class _profil_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user;
	}

	public function save($send) {
		cmfModelAdmin::save($send, $this->getId());
		if(isset($send['name'])) {
			cmfAjax::get()->loadHTML('mainUserName', $send['name']);
        }
		$this->updateData($send);
	}

	public function updateData($list, $send) {
	}

}

?>