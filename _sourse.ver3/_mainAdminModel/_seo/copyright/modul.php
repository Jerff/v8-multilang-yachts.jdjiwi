<?php


class _seo_copyright_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_seo_copyright_db');

		// �����
		$form = $this->getForm();
		$form->add('content',	new cmfFormTextarea(array('!empty')));
//		$form->add('adress',	new cmfFormTextarea(array('!empty')));
	}

}

?>