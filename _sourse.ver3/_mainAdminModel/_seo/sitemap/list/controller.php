<?php


class _seo_sitemap_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_sitemap_list_modul');

		// url
		$this->setSubmitUrl('/admin/seo/sitemap/');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>