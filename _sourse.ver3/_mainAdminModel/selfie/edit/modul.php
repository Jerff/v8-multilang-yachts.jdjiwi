<?php


class selfie_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('selfie_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('name',		    new cmfFormText(array('max'=>255, '1!empty')));
		$form->add('main',		    new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());

		$size=array();
		$size['main'] = array(null, null);
		$size['small'] = array(selfieSmallWidth, selfieSmallHeight);
		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathSelfie, 'size'=>$size, 'addField')));

		$this->setPath(cmfPathSelfie);
		$this->setSize(selfieSmallWidth, selfieSmallHeight);
	}

}

?>