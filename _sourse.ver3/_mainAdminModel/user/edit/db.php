<?php


class user_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user;
	}

	public function save($send) {
		$id = cmfUserModel::save($send, $this->getId());
		$this->saveSetId($id);
		$this->saveEnd($id, $send);
		$this->setUpdateData($send);
	}

	public function updateData($list, $send) {
	}

	public function getUserStat($id) {
		$stat = array();
		$stat['Объявлений']  = $this->getSql()->placeholder("SELECT count(`id`) FROM ?t WHERE user=?", db_board, $id)
												->fetchRow(0);
		return $stat;
	}

}

?>