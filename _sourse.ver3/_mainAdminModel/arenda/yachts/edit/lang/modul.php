<?php

class arenda_yachts_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('arenda_yachts_edit_lang_db');

        // формы
        $form = $this->getForm();
//		$form->add('shipyards',		new cmfFormSelectInt(array('!empty1')));
		$form->add('shipyardsName',	new cmfFormText(array('max'=>150)));
//		$form->add('type',		    new cmfFormRadioInt(array('!empty')));

//		$form->add('uri',			new cmfFormText(array('uri'=>100)));
		$form->add('name',		    new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('notice',		new cmfFormTextareaWysiwyng('/arenda/yachts/', $this->getId(), array('max'=>10000)));
//		$form->add('priceHour',	    new cmfFormText());
//		$form->add('priceDay',	    new cmfFormText());
//		$form->add('priceLightDay',	new cmfFormText());
//		$form->add('priceWeek',	    new cmfFormText());
//		$form->add('currency',	    new cmfFormSelect());


//		$size=array();
//		$size['main'] = array(yachtsWidth, yachtsHeight);
//		$size['small'] = array(yachtsSmallWidth, yachtsSmallHeight);
//		$size['best'] = array(yachtsBestWidth, yachtsBestHeight);
//		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathArendaYachts, 'size'=>$size, 'addField')));
		$form->add('image_title',	new cmfFormText(array('max'=>255, '1!empty')));

		$form->add('title',			new cmfFormTextarea(array('max'=>255)));
		$form->add('keywords',		new cmfFormTextarea(array('max'=>255)));
		$form->add('description',	new cmfFormTextarea(array('max'=>255)));
    }

}

?>