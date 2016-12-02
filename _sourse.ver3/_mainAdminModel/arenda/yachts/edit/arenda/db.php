<?php


class arenda_yachts_edit_arenda_db extends driver_db_edit_product_action {

	protected function getTable() {
		return db_arenda_list;
	}

	protected function action() {
		return 'arenda';
	}

	protected function product() {
		return 'yachts';
	}

	protected function getActionList() {
	    return cmfModulLoad('arenda_list_db')->getNameList();
	}

	public function updateData($list, $send) {
		cmfControllerArendaYachts::update($list);
	}

}

?>