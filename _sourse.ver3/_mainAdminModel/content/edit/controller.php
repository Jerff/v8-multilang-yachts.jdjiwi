<?php


class content_edit_controller extends driver_controller_edit_tree_lang {

	protected function init() {
		parent::init();
		$this->addModul('main',	'content_edit_modul');
		$this->addModul('lang',	'content_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/content/edit/');
		$this->setCatalogUrl('/admin/content/');
	}

    public function viewSiteUrl() {
        return parent::viewSiteUrl('/content/', $this->viewSiteData('isUri'));
    }

	public function delete($id) {
		$id = parent::delete($id);
		cmfControllerContent::delete($id);
		cmfModulLoad('main_slider_edit_controller')->deleteSlider('content', $id);
		return $id;
	}

}

?>