<?php

class brokerage_type_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('brokerage_type_edit_lang_db');

        // формы
        $form = $this->getForm();
        $form->add('name', new cmfFormTextarea(array('!empty', 'max' => 100)));
        $form->add('menu', new cmfFormText(array('max' => 100)));
        $form->add('header', new cmfFormTextarea(array('!empty', 'max' => 255)));
//		$form->add('image',	    new cmfFormFile(array('path'=>cmfPathBrokerageType)));
        $form->add('content', new cmfFormTextareaWysiwyng('brokerage/type', $this->getId()));
//		$form->add('visible',	new cmfFormCheckbox());

        $form->add('title', new cmfFormTextarea(array('max' => 1500)));
        $form->add('keywords', new cmfFormTextarea(array('max' => 1500)));
        $form->add('description', new cmfFormTextarea(array('max' => 1500)));
    }

}

?>