<?php


abstract class driver_modul_list_product_attach extends driver_modul_list {

	protected $attach = null;

	protected function init() {
		parent::init();

		// формы
		$form = $this->getForm();
		$form->add('visible', new cmfFormCheckbox());
		$form->select('visible', 'no');
	}


	protected function selectForm($data) {
		if($data==='yes') $this->getForm()->select('visible', $data);
		else $this->getForm()->select('visible', 'no');
	}

	public function deleteProduct($id) {
		$this->getDb()->deleteProduct($id);
	}

	public function deleteAttach($id) {
		$this->getDb()->deleteAttach($id);
	}

}

?>