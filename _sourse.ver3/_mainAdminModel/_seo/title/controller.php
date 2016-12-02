<?php


class _seo_title_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_title_modul');

		// url
		$this->setSubmitUrl('/admin/seo/');

		$this->callFuncWriteAdd('delete');
	}

	public function getChangeUrl() {
		$opt = array('id'=>null);
		return $this->getSubmitUrl($opt);
	}

	public function delete($id=null) {
		parent::delete($this->getId());

		$js = '';
		$js2 = '';
		foreach($this->getModulAll() as $modul) {
			$modul->resetForm();
			$js .= $modul->getJsSetData();
		}
		$this->getResponse()->addScript($js);
	}
}

?>