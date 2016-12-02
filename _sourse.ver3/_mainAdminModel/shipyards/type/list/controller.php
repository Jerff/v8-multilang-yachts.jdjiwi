<?php


class shipyards_type_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_type_list_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/type/');
		$this->setEditUrl('/admin/shipyards/type/edit/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/shipyards/type/', $arg->uri) .'<br />'. parent::viewListSiteUrl('/sale/new/type/', $arg->uri);
    }

	public function delete($id) {
		$id = cmfModulLoad('shipyards_type_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>