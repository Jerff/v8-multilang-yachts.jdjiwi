<?php


class shipyards_yachts_edit_controller extends driver_controller_gallery_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_yachts_edit_modul');
        $this->addModul('lang', 'shipyards_yachts_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/yachts/edit/');
		$this->setCatalogUrl('/admin/shipyards/yachts/');

	}

    public function viewSiteUrl() {
        $shipyards = cmfModulLoad('shipyards_edit_db')->getFeildOfId('uri', $this->viewSiteData('shipyards'));
        return parent::viewSiteUrl('/shipyards/yachts/', $shipyards, $this->viewSiteData('uri'));
    }

	public function filterMenu($id=null) {
		if($id) {
            $id = $this->getModul()->getDb()->getFeildOfId('shipyards', $id);
            $this->setFilter('shipyards', $id);
		} else {
            $id = $this->getModul()->getDataId('shipyards');
		}
		return parent::filterMenu3('Все яхты верфи', $id, 'shipyards_list_db');
	}

	public function filterMenuAll($id) {
		list($shipyards, $uri) = $this->getModul()->getDb()->getFeildsId(array('shipyards', 'uri'), $id);
		$this->setFilter('shipyards', $shipyards);
        $shipyardsUri = cmfModulLoad('shipyards_edit_db')->getFeildOfId('uri', $shipyards);
		return array(parent::filterMenu3('Все яхты верфи', $shipyards, 'shipyards_list_db'),
		             parent::viewSiteUrl2('/shipyards/yachts/', $shipyardsUri, $uri));
	}

	public function filterUrl() {
		$shipyards = $this->getModul()->getDataId('shipyards');
        $isUri = cmfModulLoad('shipyards_list_db')->getFeildOfId('uri', $shipyards);
		return array($isUri, $this->getModul()->getDataId('uri'));
	}

	public function deleteShipyards($id) {
		$this->delete($this->getListId(array('shipyards'=>$id)));
	}

	public function delete($id) {
		cmfModulLoad('shipyards_yachts_foto_edit_controller')->deleteYachts($id);
		cmfModulLoad('shipyards_yachts_plan_edit_controller')->deleteYachts($id);
		cmfModulLoad('shipyards_yachts_param_db')->deleteYachts($id);

		$id = parent::delete($id);
		cmfControllerShipyardsYachts::delete($id);
		return $id;
	}

}

?>