<?php


class brokerage_yachts_plan_list_modul extends driver_modul_gallery_list {

	protected function init() {
		parent::init();

		$this->setDb('brokerage_yachts_plan_list_db');
	}

}

?>