<?php


class shipyards_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_edit_modul');
        $this->addModul('lang', 'shipyards_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/edit/');
		$this->setCatalogUrl('/admin/shipyards/');
	}

    public function viewSiteUrl() {
        return parent::viewSiteUrl('/shipyards/one/', $this->viewSiteData('uri'));
    }

	public function delete($id) {
		$id = parent::delete($id);
		cmfModulLoad('shipyards_yachts_edit_controller')->deleteShipyards($id);
		cmfModulLoad('main_slider_edit_controller')->deleteSlider('shipyards/edit', $id);
		cmfControllerShipyards::delete($id);
		return $id;
	}

}

?>