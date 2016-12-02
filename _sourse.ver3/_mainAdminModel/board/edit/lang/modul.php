<?php


class news_edit_lang_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('news_edit_lang_db');

		// формы
		$form = $this->getForm();
		$form->add('header',		new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('notice',		new cmfFormTextarea(array('!empty')));
		$form->add('content',		new cmfFormTextareaWysiwyng('news', $this->getId()));

		$form->add('title',			new cmfFormTextarea());
		$form->add('keywords',		new cmfFormTextarea());
		$form->add('description',	new cmfFormTextarea());
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['notice']) and empty($send['notice'])) {
			$notice = $this->getForm()->handlerElement('content');
			$send['notice'] = cmfSubContent($notice);
		}
	}

}

?>