<?php


class _administrator_config_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_administrator_config_modul');

		// url
		$this->setSubmitUrl('/admin/administrator/config/');

		$this->callFuncWriteDel('delete');
	}

	public function filterGroup() {
		$filter = cmfModulLoad('_administrator_group_list_db')->getNameList();
		$filter[-1]['name'] = 'Не администраторы';
		$filter[0]['name'] = 'Все администраторы';
		return parent::abstractFilterPart($filter, 'admin', 'end');
	}

	protected function updateAccess($list) {
		foreach($list as $id) {
			cmfModelAdmin::accesIs($id);
		}
	}

}

?>