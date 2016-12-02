<?php


class brokerage_yachts_plan_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('brokerage_yachts_plan_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('name',		    new cmfFormText(array('max'=>255, '1!empty')));
		$form->add('main',		    new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());


		$size=array();
		$size['main'] = array(yachtsPlanWidth, yachtsPlanHeight);
		$size['small'] = array(yachtsPlanSmallWidth, yachtsPlanSmallHeight);
		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathBrokerageYachtsPlan, 'size'=>$size, 'addField')));

		$this->setPath(cmfPathBrokerageYachtsPlan);
		$this->setSize(yachtsPlanSmallWidth, yachtsPlanSmallHeight);
	}

	protected function saveStart(&$send) {
		if(count($send) and !$this->getId()) {
			 $send['yachts'] = cmfAdminMenu::getSubMenuId();
		}
		parent::saveStart($send);
	}

}

?>