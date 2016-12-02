<?php


class arenda_yachts_param_modul extends driver_modul_edit_param {

	protected function init() {
		parent::init();

		$this->setDb('arenda_yachts_param_db');
	}

}

?>