<?php


class shipyards_yachts_foto_list_modul extends driver_modul_gallery_list {

	protected function init() {
		parent::init();

		$this->setDb('shipyards_yachts_foto_list_db');
	}

    protected function saveStart(&$send) {
        if(!empty($send)) { $send['time'] = time(); }
		parent::saveStart($send);
    }

}

?>