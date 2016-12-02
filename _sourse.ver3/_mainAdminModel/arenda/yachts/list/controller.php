<?php


class arenda_yachts_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'arenda_yachts_list_modul');

		// url
		$this->setSubmitUrl('/admin/arenda/yachts/');
		$this->setEditUrl('/admin/arenda/yachts/edit/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_args();
        return parent::viewListSiteUrl('/arenda/yachts/', get2($arg[0], $arg[1]->type, 'uri'), $arg[1]->uri);
    }

	public function filterShipyards() {
		$filter = cmfModulLoad('shipyards_list_db')->getNameList(null, array('uri'));
		cmfGlobal::set('$sectionId', array_keys($filter));
		$filter[-1]['name'] = 'Без верфи';
		$filter[0]['name'] = 'Все верфи';
		return parent::abstractFilterPart($filter, 'shipyards', 'end');
	}

	public function filterArenda() {
		$filter = cmfModulLoad('arenda_list_db')->getNameList(null, array('isUri'));
		cmfGlobal::set('$arendaId', array_keys($filter));
		$filter[0]['name'] = 'Без разделов';
		$filter[-1]['name'] = 'Все разделы';
		return parent::abstractFilterPart($filter, 'arenda', 'end');
	}

	public function filterType() {
		$filter = cmfModulLoad('arenda_type_list_db')->getNameList(null, array('uri'));
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'type', 'end');
	}

	public function delete($id) {
		$id = cmfModulLoad('arenda_yachts_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>