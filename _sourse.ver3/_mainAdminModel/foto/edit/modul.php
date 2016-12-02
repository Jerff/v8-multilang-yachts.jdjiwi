<?php


class foto_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('foto_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('name',		    new cmfFormText(array('max'=>255, '1!empty')));
		$form->add('main',		    new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());


		$size=array();
		$size['main'] = array(null, null);
		$size['small'] = array(fotoSmallWidth, fotoSmallHeight);
		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathFoto, 'size'=>$size, 'addField')));


		$this->setPath(cmfPathFoto);
		$this->setSize(fotoSmallWidth, fotoSmallHeight);
	}

}

?>