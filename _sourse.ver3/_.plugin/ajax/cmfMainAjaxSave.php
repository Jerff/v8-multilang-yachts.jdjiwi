<?php

cmfLoad('ajax/cmfMainAjax');
class cmfMainAjaxSave extends cmfMainAjax {


	public function formSave() {
		cmfViewAjax::formSave($this);
	}

	public function loadData() {
	}

	protected function runStart() {
        $r = cmfRegister::getRequest();

		$isError = $isUpdate = false;
		$data = array();
		foreach($this->getFormAll() as $form) {
			$form->setRequest($r);
			$send = $form->processing();
			$isUpdate |= count($send);
			$data[] = $send;
		    $isError |= $form->isError();

		    $form->handler();
		}

		if(!$isError and $isUpdate) {
            return $this->getFormCount()>1 ? $data : $data[0];
		}
		$this->runEnd(true);
	}

	protected function runError($error=null) {
		parent::runError($error);
		$this->runSave(false);
	}

	protected function runSaveOk() {
		$this->runSave(true);
		$this->runEnd();
	}

	protected function runSave($action) {
		static $i = false;
		if($i) return;
		$i = true;

		$idForm = $this->getIdForm();
		$idHash = $this->getIdHash();
		if($action) {
			$js = "style.show('#{$idForm}Save');";
		} else {
			$js = "style.hide('#{$idForm}Save');";
		}
        $js .= "\n//document.location.hash = '$idHash';";
		cmfAjax::get()->addScript($js);
	}

}

?>