<?php


cmfLoadRequest();
cmfLoadAjax();
cmfLoadForm();
cmfLoad('mail/cmfMail');
cmfLoad('ajax/cmfViewAjax');


cmfLoad('driver/cmfDriverPrivateConfiguration');
class cmfMainAjax extends cmfDriverPrivateConfiguration {

	private $formUrl=null;
	private $form = null;

	private $name = null;
	private $func = null;

	function __construct($formUrl=null, $name=null, $func=null, $count=1) {

		$this->setFormUrl($formUrl);
		$this->setName($name);
		$this->setFunc($func);
		$this->setForm($count, $name);

		$this->init();
	}


	protected function init() {
	}


	public function &getForm($id=1) {
		return $this->form[$id];
	}
	public function &getFormAll() {
		return $this->form;
	}
	public function getFormCount() {
		return count($this->form);
	}
	protected function setForm($count, $name) {
		for($i=1; $i<=$count; $i++) {
			$this->form[$i] = new cmfForm('', $name, array('color'=>'#fff'));
		}
	}


	public function getName() {
		return $this->name;
	}
	protected function setName($name) {
		return $this->name = $name;
	}


	public function getFunc() {
		return $this->func;
	}
	protected function setFunc($func) {
		return $this->func = $func;
	}


	protected function setFormUrl($formUrl) {
		$this->formUrl = $formUrl;
	}
	public function getFormUrl() {
		return $this->formUrl;
	}

	public function getAjaxUrl() {
        return $this->getFormUrl() .'&action='. $this->getName();
	}



	public function getIdForm() {
		return $this->getName();
	}
	public function getIdHash() {
		return $this->getIdForm() .'Hash';
	}

	public function startForm($style='', $name='') {
		cmfViewAjax::startForm($this, $style, $name);
	}
	public function formError() {
		cmfViewAjax::formError($this);
	}
	public function endForm() {
		cmfViewAjax::endForm($this);
	}




	protected function runStart() {
        $r = cmfRegister::getRequest();

		$isError = $isUpdate = false;
		$data = array();
		foreach($this->getFormAll() as $form) {
			$form->setRequest($r);
			$send = $form->handler();
			$isUpdate |= count($send);
			$data[] = $send;
		    $isError |= $form->isError();
		}

		if(!$isError and $isUpdate) {
            return $this->getFormCount()>1 ? $data : $data[0];
		}
		$this->runEnd(true);
	}


	protected function runEnd($error=false) {
		$js = '';
		foreach($this->getFormAll() as $i=>$form) {
			$js .= $form->jsUpdate(!$error);
		}
		cmfAjax::get()->addScript($js);
		$this->runError($error);
		exit;
	}


	protected function runError($error=null) {
		$idForm = $this->getIdForm();
		$idHash = $this->getIdHash();
		if($error and is_string($error)) {
			$js = "
			$('#{$idForm}Error').show();
			$('#{$idForm}Error > b').text('". cmfToJsString($error) ."');
			/*alert('Форма не отправлена!');*/";
		} else {
			$js = "
			$('#{$idForm}Error').hide();";
		}
		cmfAjax::get()->addScript($js);
	}

}

?>