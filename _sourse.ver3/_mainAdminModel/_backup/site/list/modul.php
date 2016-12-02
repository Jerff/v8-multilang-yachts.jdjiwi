<?php


class _backup_site_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_backup_site_list_db');

		// формы
		$form = $this->getForm();
		$form->add('name',	    new cmfFormText(array('max'=>150)));
		$form->add('backup',	new cmfFormSelectCheckbox());
		$form->add('time',	    new cmfFormSelect(array('!empty')));
		$form->add('visible',	new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->getForm();

        foreach(cmfBackupConfig::menu() as $k=>$v) {
			$form->addElement('backup', $k, $v);
        }

        $form->addElement('time', '', 'Не выбрано');
        $form->addElement('time', 'day', 'Раз в сутки');
        $form->addElement('time', 'week', 'Раз в неделю');
	}

}

?>