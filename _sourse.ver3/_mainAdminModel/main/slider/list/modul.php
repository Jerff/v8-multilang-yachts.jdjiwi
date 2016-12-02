<?php


class main_slider_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('main_slider_list_db');

        $form = $this->getForm();
        $this->setNewPos();

		$form->add('main',		    new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		if(isset($send['main']) or isset($send['pos'])) {
    		$this->setNewView();
        }
		parent::saveStart($send);
	}

}

?>