<?php


class brokerage_type_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'brokerage_type_list_modul');

		// url
		$this->setSubmitUrl('/admin/brokerage/type/');
		$this->setEditUrl('/admin/brokerage/type/edit/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/brokerage/type/', $arg->uri);
    }

	public function delete($id) {
		$id = cmfModulLoad('brokerage_type_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>