<?php


class _config_cron_config_modul extends driver_modul_edit {


	protected function init() {
		parent::init();

		$this->setDb('_config_cron_config_db');

		// формы
		$form = $this->getForm();
		//$form->add('time',	new cmfFormSelect());
	}

	public function loadForm() {
		parent::loadForm();
	}

}

?>