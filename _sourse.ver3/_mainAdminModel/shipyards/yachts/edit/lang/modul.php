<?php

class shipyards_yachts_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('shipyards_yachts_edit_lang_db');

        // формы
        $form = $this->getForm();
//		$form->add('shipyards',		new cmfFormSelectInt(array('!empty')));
//		$form->add('type',		    new cmfFormRadioInt(array('!empty')));
//
//		$form->add('uri',			new cmfFormText(array('uri'=>100)));
		$form->add('name',		    new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('notice',		new cmfFormTextareaWysiwyng('shipyards_yachts', $this->getId(), array('max'=>10000)));
//		$form->add('price',	        new cmfFormTextInt());
//		$form->add('currency',	    new cmfFormSelect());


//		$size=array();
//		$size['main'] = array(yachtsWidth, yachtsHeight);
//		$size['small'] = array(yachtsSmallWidth, yachtsSmallHeight);
//		$size['best'] = array(yachtsBestWidth, yachtsBestHeight);
//		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathShipyardsYachts, 'size'=>$size, 'addField')));
		$form->add('image_title',	new cmfFormText(array('max'=>255, '1!empty')));

		$form->add('title',			new cmfFormTextarea(array('max'=>255)));
		$form->add('keywords',		new cmfFormTextarea(array('max'=>255)));
		$form->add('description',	new cmfFormTextarea(array('max'=>255)));

//		$form->add('catalog',	        new cmfFormCheckbox());
//		$form->add('visible',	        new cmfFormCheckbox());
    }

}

?>