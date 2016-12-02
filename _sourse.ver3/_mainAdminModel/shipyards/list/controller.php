<?php


class shipyards_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_list_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/');
		$this->setEditUrl('/admin/shipyards/edit/');
		$this->setUrl('yachts', '/admin/shipyards/yachts/');
	}

	public function filterLogo() {
		$filter = array();
		$filter[1]['name'] = 'Нет лого для меню';
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'logo', 'end');
	}


    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/shipyards/one/', $arg->uri);
    }

	public function delete($id) {
		$id = cmfModulLoad('shipyards_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function getYachtsUrl() {
		$opt = array('shipyards'=>$this->getIndex());
		return $this->getUrl('yachts', $opt);
	}

}

?>