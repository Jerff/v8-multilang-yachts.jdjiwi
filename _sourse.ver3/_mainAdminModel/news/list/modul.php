<?php


class news_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('news_list_db');

		// формы
		$form = $this->getForm();
		$form->add('main',		new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
        if(isset($send['main'])) {
			$this->setNewView();
        }
	}

}

?>