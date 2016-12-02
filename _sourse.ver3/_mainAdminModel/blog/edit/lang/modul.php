<?php


class blog_edit_lang_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('blog_edit_lang_db');

		// формы
		$form = $this->getForm();
		$form->add('name',		    new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('notice',		new cmfFormTextareaWysiwyng('blog', $this->getId(), array('max'=>10000)));
		$form->add('content',		new cmfFormTextareaWysiwyng('blog', $this->getId(), array('max'=>10000)));


		$form->add('title',			new cmfFormTextarea(array('max'=>255)));
		$form->add('keywords',		new cmfFormTextarea(array('max'=>255)));
		$form->add('description',	new cmfFormTextarea(array('max'=>255)));
	}

}

?>