<?php


class menu_info_edit_controller extends driver_controller_edit_tree_lang {

	protected function init() {
		parent::init();
		$this->addModul('main',	'menu_info_edit_modul');
		$this->addModul('lang',	'menu_info_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/menu/info/edit/');
		$this->setCatalogUrl('/admin/menu/info/');
	}

    public function viewSiteUrl() {
        return parent::viewSiteUrl('/info/', $this->viewSiteData('isUri'));
    }

	public function delete($id) {
		$id = parent::delete($id);
		cmfControllerInfo::delete($id);
		cmfModulLoad('main_slider_edit_controller')->deleteSlider('info', $id);
		return $id;
	}

}

?>