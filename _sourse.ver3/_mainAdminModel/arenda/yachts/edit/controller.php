<?php

class arenda_yachts_edit_controller extends driver_controller_gallery_edit {

    protected function init() {
        parent::init();
        $this->addModul('main', 'arenda_yachts_edit_modul');
        $this->addModul('arenda', 'arenda_yachts_edit_arenda_modul');
        $this->addModul('lang', 'arenda_yachts_edit_lang_modul');

        // url
        $this->setSubmitUrl('/admin/arenda/yachts/edit/');
        $this->setCatalogUrl('/admin/arenda/yachts/');
    }

    public function viewSiteUrl() {
        $type = cmfModulLoad('arenda_type_edit_db')->getFeildOfId('uri', $this->viewSiteData('type'));
        return parent::viewSiteUrl('/arenda/yachts/', $type, $this->viewSiteData('uri'));
    }

    public function filterMenuAll($id) {
        list($type, $uri) = $this->getModul()->getDb()->getFeildsId(array('type', 'uri'), $id);
        $typeUri = cmfModulLoad('arenda_type_edit_db')->getFeildOfId('uri', $type);
        return parent::viewSiteUrl2('/arenda/yachts/', $type, $uri);
    }

    public function filterUrl() {
        $shipyards = $this->getModul()->getDataId('shipyards');
        $isUri = cmfModulLoad('shipyards_list_db')->getFeildOfId('uri', $shipyards);
        return array($isUri, $this->getModul()->getDataId('uri'));
    }

    public function delete($id) {
        cmfModulLoad('arenda_yachts_foto_edit_controller')->deleteYachts($id);
        cmfModulLoad('arenda_yachts_plan_edit_controller')->deleteYachts($id);
        cmfModulLoad('arenda_yachts_param_db')->deleteYachts($id);

        $id = parent::delete($id);
        cmfControllerArendaYachts::delete($id);
        return $id;
    }

}

?>