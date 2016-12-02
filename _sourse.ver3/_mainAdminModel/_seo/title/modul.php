<?php


class _seo_title_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_seo_title_db');

		// формы
		$form = $this->getForm();
		$form->add('title',		new cmfFormTextarea());
		$form->add('keywords',	new cmfFormTextarea());
		$form->add('description',	new cmfFormTextarea());
		$form->add('value',		new cmfFormValue());
		$form->add('uri',			new cmfFormSelect());

		$element = $this->getForm()->get('uri');
		cmfModulLoad('_pages_main_list_db')->getSelectTree($element);

		$id = $this->getId();
		if(!$id) {
			$id = current($element->getValueArray());
			$this->setId($id);
		}
        $element->select($id);
	}


	protected function selectForm($data) {
		parent::selectForm($data);
		$value = cmfModulLoad('_pages_main_list_db')->getPagesVariables($this->getId());
		if($value) {
			$this->getForm()->select('value', $value);
		}
	}

	public function loadForm() {
		$value = cmfModulLoad('_pages_main_list_db')->getPagesVariables($this->getId());
		if($value) {
			$this->getForm()->select('value', $value);
		}
		$this->getForm()->select('uri', $this->getId());
	}


	public function save($data) {
		if(count($data)) {
			$this->getDb()->save($data);
			$this->updateForm();
			return true;
		}
		return false;
	}


	public function delete($listId=null) {
		$listId = parent::delete($listId);
		$this->updateForm();
		return $listId;
	}

	public function resetForm(){
		$this->getForm()->reset();
		$this->getForm()->select('uri', $this->getId());
	}

	protected function updateForm(){
		$element = $this->getForm()->get('uri');
		cmfModulLoad('_pages_main_list_db')->getSelectTree($element);
	}

}

?>