<?php


class param_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('param_edit_db');

		// формы
		$form = $this->getForm();

//		$form->add('name',	    new cmfFormText(array('max'=>50, '!empty')));
//		$form->add('prefix',	new cmfFormText(array('max'=>25)));

		//$form->add('search',	new cmfFormTextarea(array('1!empty')));
		//$form->add('prefixParse',	new cmfFormTextarea(array('1!empty')));
		//$form->add('parseType',	new cmfFormSelect());

//		$form->add('notice',	new cmfFormTextareaWysiwyng('param', $this->getId()));

		$form->add('type',	new cmfFormSelect(array('!empty')));
		$form->add('visible',	new cmfFormCheckbox());
	}

//	protected function updateIsErrorData($data, &$isError) {
//		if(!empty($data['name'])) {
//			$is = cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE `name`=? AND id!=?", db_param_lang, $data['name'], $this->getId())
//										->numRows();
//			if($is) {
//				$this->getForm()->setError('name', 'Параметр "'. $data['name'] .'" уже существует!');
//				$isError = true;
//			}
//		}
//	}

	public function loadForm() {

		$form = $this->getForm();
		$form->addElement('type', '', 'Не выбрано');
		$form->addElement('type', 'text', 'text');
		$form->addElement('type', 'textarea', 'textarea');
		$form->addElement('type', 'select', 'select');
		$form->addElement('type', 'radio', 'radio');
		$form->addElement('type', 'checkbox', 'checkbox');

/*		$form->addElement('parseType', '', 'Не выбрано');
		$form->addElement('parseType', 'int', 'int');
		$form->addElement('parseType', 'float.1', 'float.1');
		$form->addElement('parseType', 'float.2', 'float.2');*/
	}

}

?>