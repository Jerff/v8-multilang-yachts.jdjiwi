<?php


class arenda_yachts_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('arenda_yachts_list_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('visible',	new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		if(isset($send['pos']) and (empty($send['pos']) or $send['pos']>9999)) {
				$send['pos'] = 99;
		}
		parent::saveStart($send);
	}

}

?>