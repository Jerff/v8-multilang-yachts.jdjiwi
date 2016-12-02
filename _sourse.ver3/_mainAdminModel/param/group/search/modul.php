<?php


class param_group_search_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('param_group_search_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('param',		new cmfFormSelect(array('max'=>20)));
		$form->add('view',		new cmfFormSelect(array('max'=>20)));
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->getForm();
		foreach(cmfAdminSiteMenu::initParamMenu() as $k=>$v) {
            $form->addElement('param', $k, $v);
		}
		$form->addElement('view', 'range', 'диапазон');
		$form->addElement('view', 'checkbox', 'множественный выбор значений параметра (checkbox)');
		$form->addElement('view', 'radio', 'выбор значения параметра (radio)');
	}

	protected function saveStart(&$send) {
		if(!$this->getId()) {
			 $send['group'] = $this->getFilter('group');
		}
		parent::saveStart($send);
	}

}

?>