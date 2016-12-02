<?php


class main_showcase_controller extends driver_controller_list_all {

    function __construct($id=null) {
        $this->setIdName('image');
        parent::__construct($id);
	}

	protected function init() {
		parent::init();
		$this->addModul('main',	'main_showcase_modul');

		// url
		$this->setSubmitUrl('/admin/main/showcase/');
		$this->callFuncReadAdd('onchangePage');
	}

    public function loadForm2() {
        return $this->getModul()->loadForm2($this->getId());
	}

	protected function onchangePage($id) {
		$this->getModul()->onchangePage($id);
	}

	public function delete($id) {
		parent::delete($id);
		$this->getResponse()->redirect($this->getSubmitUrl());
	}

}

?>