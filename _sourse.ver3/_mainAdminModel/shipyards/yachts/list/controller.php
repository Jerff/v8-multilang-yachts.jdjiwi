<?php


class shipyards_yachts_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_yachts_list_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/yachts/');
		$this->setEditUrl('/admin/shipyards/yachts/edit/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_args();
        return parent::viewListSiteUrl('/shipyards/yachts/', get2($arg[0], $arg[1]->shipyards, 'uri'), $arg[1]->uri);
    }

	public function filterShipyards() {
		$filter = cmfModulLoad('shipyards_list_db')->getNameList(null, array('uri'));
		cmfGlobal::set('$sectionId', array_keys($filter));
		$filter[-1]['name'] = 'Без верфи';
		$filter[0]['name'] = 'Все верфи';
		return parent::abstractFilterPart($filter, 'shipyards', 'end');
	}

	public function filterType() {
		$filter = cmfModulLoad('shipyards_type_list_db')->getNameList();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'type', 'end');
	}

	public function filterCatalog() {
		$filter = array();
		$filter[1]['name'] = 'Показывать отмеченные яхты';
		$filter[2]['name'] = 'Показывать не отмеченные яхты';
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'catalog', 'end');
	}

	public function delete($id) {
		$id = cmfModulLoad('shipyards_yachts_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>