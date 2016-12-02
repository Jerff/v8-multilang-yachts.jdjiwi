<?php

class brokerage_type_edit_controller extends driver_controller_lang_edit {

    protected function init() {
        parent::init();
        $this->addModul('main', 'brokerage_type_edit_modul');
        $this->addModul('lang', 'brokerage_type_edit_lang_modul');

        // url
        $this->setSubmitUrl('/admin/brokerage/type/edit/');
        $this->setCatalogUrl('/admin/brokerage/type/');
    }

    public function viewSiteUrl() {
        return parent::viewSiteUrl('/brokerage/type/', $this->viewSiteData('uri'));
    }

    public function delete($id) {
        $id = parent::delete($id);
        cmfConfigBrokerageType::delete($id);
        cmfModulLoad('main_slider_edit_controller')->deleteSlider('brokerage/type', $id);
        return $id;
    }

}

?>