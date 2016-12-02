<?php


class shipyards_yachts_param_db extends driver_db_edit_param {


	protected function getTable() {
		return db_shipyards_yachts;
	}

	protected function getGroup() {
		return 'shipyards';
	}

	public function paramList() {
		return cmfModulLoad('param_list_db')->getParamList('shipyards', array('visible'=>'yes'));
	}

	public function updateData($param) {
        cmfControllerShipyardsYachts::update($this->getId());
	}

}

?>