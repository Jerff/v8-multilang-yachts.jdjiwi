<?php


class foto_list_modul extends driver_modul_gallery_list {

	protected function init() {
		parent::init();

		$this->setDb('foto_list_db');
	}

}

?>