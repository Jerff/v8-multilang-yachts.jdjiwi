<?php

class brokerage_yachts_edit_controller extends driver_controller_gallery_edit {

    protected function init() {
        parent::init();
        $this->addModul('main', 'brokerage_yachts_edit_modul');
        $this->addModul('lang', 'brokerage_yachts_edit_lang_modul');

        // url
        $this->setSubmitUrl('/admin/brokerage/yachts/edit/');
        $this->setCatalogUrl('/admin/brokerage/yachts/');
    }

    public function viewSiteUrl() {
        $type = cmfModulLoad('brokerage_type_edit_db')->getFeildOfId('uri', $this->viewSiteData('type'));
        return parent::viewSiteUrl('/brokerage/yachts/', $type, $this->viewSiteData('uri'));
    }

    public function filterMenuAll($id) {
        list($type, $uri) = $this->getModul()->getDb()->getFeildsId(array('type', 'uri'), $id);
        $typeUri = cmfModulLoad('brokerage_type_edit_db')->getFeildOfId('uri', $type);
        return parent::viewSiteUrl2('/brokerage/yachts/', $typeUri, $uri);
    }

    public function filterUrl() {
        $shipyards = $this->getModul()->getDataId('shipyards');
        $isUri = cmfModulLoad('shipyards_list_db')->getFeildOfId('uri', $shipyards);
        return array($isUri, $this->getModul()->getDataId('uri'));
    }

    public function delete($id) {
        cmfModulLoad('brokerage_yachts_foto_edit_controller')->deleteYachts($id);
        cmfModulLoad('brokerage_yachts_plan_edit_controller')->deleteYachts($id);
        cmfModulLoad('brokerage_yachts_param_db')->deleteYachts($id);

        $id = parent::delete($id);
        cmfConfigBrokerageYachts::delete($id);
        return $id;
    }

}

?>