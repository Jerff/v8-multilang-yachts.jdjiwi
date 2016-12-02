<?php


class news_edit_controller extends driver_controller_lang_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'news_edit_modul');
		$this->addModul('lang',	'news_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/news/edit/');
		$this->setCatalogUrl('/admin/news/');
	}

    public function viewSiteUrl() {
        $data = $this->getModul()->getData();
        return parent::viewSiteUrl('/news/item/', $this->viewSiteData('uri'));
    }

	public function delete($id) {
		$id = parent::delete($id);
		cmfControllerNews::delete($id);
		cmfModulLoad('main_slider_edit_controller')->deleteSlider('news/edit', $id);
		return $id;
	}

}

?>