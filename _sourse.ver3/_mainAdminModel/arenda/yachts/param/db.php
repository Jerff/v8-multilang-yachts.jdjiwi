<?php


class arenda_yachts_param_db extends driver_db_edit_param {


	protected function getTable() {
		return db_arenda_yachts;
	}

	protected function getGroup() {
		return 'brokerage';
	}

	public function paramList() {
		return cmfModulLoad('param_list_db')->getParamList('arenda', array('visible'=>'yes'));
	}

	public function updateData($param) {
        cmfControllerArendaYachts::update($this->getId());
	}

}

?>