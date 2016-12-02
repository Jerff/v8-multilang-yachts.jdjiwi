<?php


class arenda_yachts_edit_arenda_modul extends driver_modul_edit_product_action {

	protected function init() {
		parent::init();

		$this->setDb('arenda_yachts_edit_arenda_db');
	}

}

?>