<?php


class selfie_list_modul extends driver_modul_gallery_list {

	protected function init() {
		parent::init();

		$this->setDb('selfie_list_db');
	}

}

?>