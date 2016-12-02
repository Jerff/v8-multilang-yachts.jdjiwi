<?php


class brokerage_yachts_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'brokerage_yachts_list_modul');

		// url
		$this->setSubmitUrl('/admin/brokerage/yachts/');
		$this->setEditUrl('/admin/brokerage/yachts/edit/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_args();
        return parent::viewListSiteUrl('/brokerage/yachts/', get2($arg[0], $arg[1]->type, 'uri'), $arg[1]->uri);
    }

	public function filterShipyards() {
		$filter = cmfModulLoad('shipyards_list_db')->getNameList(null, array('uri'));
		cmfGlobal::set('$sectionId', array_keys($filter));
		$filter[-1]['name'] = 'Без разделов';
		$filter[0]['name'] = 'Все разделы';
		return parent::abstractFilterPart($filter, 'shipyards', 'end');
	}

	public function filterType() {
		$filter = cmfModulLoad('brokerage_type_list_db')->getNameList(null, array('uri'));
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'type', 'end');
	}

	public function delete($id) {
		$id = cmfModulLoad('brokerage_yachts_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>