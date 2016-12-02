<?php


class param_group_notice_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('param_group_notice_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('param',		new cmfFormSelect(array('max'=>20)));
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->getForm();
		foreach(cmfAdminSiteMenu::initParamMenu() as $k=>$v) {
            $form->addElement('param', $k, $v);
		}
	}

	protected function saveStart(&$send) {
		if(!$this->getId()) {
			 $send['group'] = $this->getFilter('group');
		}
		parent::saveStart($send);
	}

}

?>