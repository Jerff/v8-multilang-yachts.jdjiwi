<?php


class shipyards_type_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_type_edit_modul');
        $this->addModul('lang', 'shipyards_type_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/type/edit/');
		$this->setCatalogUrl('/admin/shipyards/type/');
	}

    public function viewSiteUrl() {
        return parent::viewSiteUrl('/shipyards/type/', $this->viewSiteData('uri'));
    }

    public function saleWiewSiteUrl() {
        return parent::viewSiteUrl('/sale/new/type/', $this->viewSiteData('uri'));
    }

	public function delete($id) {
		$id = parent::delete($id);
		cmfControllerShipyardsType::delete($id);
		cmfModulLoad('main_slider_edit_controller')->deleteSlider('shipyards/type', $id);
		return $id;
	}

}

?>