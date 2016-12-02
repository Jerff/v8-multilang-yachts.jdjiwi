<?php


class article_edit_controller extends driver_controller_lang_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'article_edit_modul');
		$this->addModul('lang',	'article_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/article/edit/');
		$this->setCatalogUrl('/admin/article/');
	}

    public function viewSiteUrl() {
        $data = $this->getModul()->getData();
        return parent::viewSiteUrl('/articles/item/', $this->viewSiteData('uri'));
    }

	public function delete($id) {
		$id = parent::delete($id);
		cmfControllerArticle::delete($id);
		cmfModulLoad('main_slider_edit_controller')->deleteSlider('article/one', $id);
		return $id;
	}

}

?>