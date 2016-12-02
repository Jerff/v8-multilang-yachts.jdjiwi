<?php


class brokerage_yachts_foto_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('brokerage_yachts_foto_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('name',		    new cmfFormText(array('max'=>255, '1!empty')));
		$form->add('main',		    new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());


		$size=array();
		$size['main'] = array(yachtsFotoWidth, yachtsFotoHeight);
		$size['small'] = array(yachtsFotoSmallWidth, yachtsFotoSmallHeight);
		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathBrokerageYachtsFoto, 'size'=>$size, 'addField')));

		$this->setPath(cmfPathBrokerageYachtsFoto);
		$this->setSize(yachtsFotoSmallWidth, yachtsFotoSmallHeight);
	}

	protected function saveStart(&$send) {
		if(count($send) and !$this->getId()) {
			 $send['yachts'] = cmfAdminMenu::getSubMenuId();
		}
		if(!empty($send)) { $send['time'] = time(); }
		parent::saveStart($send);
	}

}

?>