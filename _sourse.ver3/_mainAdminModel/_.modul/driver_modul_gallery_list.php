<?php


abstract class driver_modul_gallery_list extends driver_modul_list {


	protected function init() {
		parent::init();

		// формы
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