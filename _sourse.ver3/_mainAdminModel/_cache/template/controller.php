<?php


class _cache_template_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->setSubmitUrl('/admin/cache/template/');

		$this->callFuncWriteAdd('clearFile');
	}

	protected function clearFile() {
        cmfDir::clear(cmfCompileModel);
        cmfDir::clear(cmfCompileController);
	}

}

?>