<?php


class _lang_list_section_modul extends driver_modul_edit_product_action {

	function __construct() {
		$this->setIdName('main');
		parent::__construct();
	}

	protected function init() {
		parent::init();

		$this->setDb('_lang_list_section_db');
	}

	public function loadForm() {
		// формы
		$form = $this->getForm();
		foreach($this->getDb()->getAction() as $key=>$value) {
			$form->add($key, new cmfFormCheckbox(array('label'=>$value['name'])));
		}
	}

	protected function selectForm($data) {
		$form = $this->getForm();
		foreach($this->getDb()->getActionKey() as $action)
			if(isset($data[$action])) $form->select($action, 'yes');
			else  $form->select($action, 'no');
	}

}

?>