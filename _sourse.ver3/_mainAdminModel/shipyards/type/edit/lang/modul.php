<?php

class shipyards_type_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('shipyards_type_edit_lang_db');

        // формы
        $form = $this->getForm();
//		$form->add('uri',		    new cmfFormText(array('uri'=>25)));
		$form->add('name',	        new cmfFormTextarea(array('!empty', 'max'=>100)));
		$form->add('header',	    new cmfFormTextarea(array('!empty', 'max'=>255)));
		$form->add('menu',	        new cmfFormText(array('max'=>100)));
//		$form->add('image',	        new cmfFormFile(array('path'=>cmfPathShipyardsType)));
		$form->add('notice',	    new cmfFormTextareaWysiwyng('shipyards_type', $this->getId()));
		$form->add('content',	    new cmfFormTextareaWysiwyng('shipyards_type', $this->getId()));
//		$form->add('visible',	    new cmfFormCheckbox());

		$form->add('title',			new cmfFormTextarea(array('max'=>255)));
		$form->add('keywords',		new cmfFormTextarea(array('max'=>255)));
		$form->add('description',	new cmfFormTextarea(array('max'=>255)));


		$form->add('header2',	    new cmfFormTextarea(array('!empty', 'max'=>255)));
		$form->add('content2',	    new cmfFormTextareaWysiwyng('shipyards_type', $this->getId()));
		$form->add('title2',		new cmfFormTextarea(array('max'=>255)));
		$form->add('keywords2',		new cmfFormTextarea(array('max'=>255)));
		$form->add('description2',	new cmfFormTextarea(array('max'=>255)));
    }

}

?>