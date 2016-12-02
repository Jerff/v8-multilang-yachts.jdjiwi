<?php


class brokerage_yachts_param_db extends driver_db_edit_param {


	protected function getTable() {
		return db_brokerage_yachts;
	}

	protected function getGroup() {
		return 'brokerage';
	}

	public function paramList() {
		return cmfModulLoad('param_list_db')->getParamList('brokerage', array('visible'=>'yes'));
	}

	public function updateData($param) {
        cmfConfigBrokerageYachts::update($this->getId());
	}

}

?>