<?php


class shipyards_yachts_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('shipyards_yachts_list_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('catalog',	new cmfFormCheckbox());
		$form->add('visible',	new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		if(isset($send['pos']) and (empty($send['pos']) or $send['pos']>99)) {
				$send['pos'] = 99;
		}
		parent::saveStart($send);
	}

}

?>