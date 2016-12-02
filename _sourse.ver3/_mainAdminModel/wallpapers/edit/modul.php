<?php


class wallpapers_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('wallpapers_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('name',		    new cmfFormText(array('max'=>255, '1!empty')));
		$form->add('main',		    new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());

		$size=array();
		$size['main'] = array(null, null);
		$size['small'] = array(wallpapersSmallWidth, wallpapersSmallHeight);
		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathWallpapers, 'size'=>$size, 'addField')));

		$this->setPath(cmfPathWallpapers);
		$this->setSize(wallpapersSmallWidth, wallpapersSmallHeight);
	}

}

?>