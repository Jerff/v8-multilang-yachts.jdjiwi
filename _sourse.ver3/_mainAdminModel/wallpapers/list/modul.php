<?php


class wallpapers_list_modul extends driver_modul_gallery_list {

	protected function init() {
		parent::init();

		$this->setDb('wallpapers_list_db');
	}

}

?>