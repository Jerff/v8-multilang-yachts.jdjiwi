<?php


class _lang_list_config_controller extends driver_controller_edit_param_of_record {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_lang_list_config_modul');
		$this->addModul('section',	'_lang_list_section_modul');

		// url
		$this->setSubmitUrl('/admin/lang/');
	}

}

?>