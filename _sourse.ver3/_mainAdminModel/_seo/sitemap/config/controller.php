<?php


class _seo_sitemap_config_controller extends driver_controller_edit {

	function __construct($id=null) {
		$this->setIdName('main');
		parent::__construct($id);
	}

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_sitemap_config_modul');

		// url
		$this->setSubmitUrl('/admin/seo/sitemap/');

		$this->callFuncWriteAdd('updateSitemap');
	}

	protected function updateSitemap() {
        cmfCronConfig::runModul('siteMap');
        $this->getResponse()->addAlert('Обновление завершено');
	}

}

?>