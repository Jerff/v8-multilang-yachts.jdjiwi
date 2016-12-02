<?php

class shipyards_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('shipyards_edit_lang_db');

        // формы
        $form = $this->getForm();
		$form->add('name',	    new cmfFormTextarea(array('!empty', 'max'=>100)));
		$form->add('content',	new cmfFormTextareaWysiwyng('shipyards', $this->getId()));

		$form->add('image_title',	new cmfFormText(array('!empty', 'max'=>100)));
        $form->add('land_title',	new cmfFormText(array('!empty', 'max'=>100)));

		$form->add('title',			new cmfFormTextarea());
		$form->add('keywords',		new cmfFormTextarea());
		$form->add('description',	new cmfFormTextarea());
    }

}

?>