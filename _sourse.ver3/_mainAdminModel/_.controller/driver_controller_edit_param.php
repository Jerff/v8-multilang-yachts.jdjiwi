<?php


abstract class driver_controller_edit_param extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->callFuncWriteAdd('paramAdd|paramDel|paramDelChecbox');
	}


	protected function paramAdd($param, $newId) {
		$new = $this->getRequest()->getPost($newId);
		if(empty($new)) return;
		$this->getModul()->paramAdd($param, $new);
		$this->getResponse()->addScript("cmf.setValue('{$newId}', '')");
	}

	protected function paramDel($param) {
		$name = $this->getModul()->getForm()->getId($param);
		$delete  = $this->getRequest()->getPost($name);
		$this->getModul()->paramDel($param, $delete);
	}

	protected function paramDelChecbox($param) {
        $delete  = $this->getRequest()->getPost('delete'. $param);
        if($delete!=-1) {
    		$this->getModul()->paramDel($param, $delete);
        }
	}

	public function paramView($isBasket=false) {
		view_param::paramView($this->getModulName(),
								$this->getModul()->getForm(),
								$this->getModul()->getParam(),
								$isBasket);
	}

}

?>