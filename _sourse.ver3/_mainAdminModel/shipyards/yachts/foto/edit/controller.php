<?php


class shipyards_yachts_foto_edit_controller extends driver_controller_gallery_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_yachts_foto_edit_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/yachts/foto/');
		$this->setCatalogUrl('/admin/shipyards/yachts/foto/');
	}

	public function deleteYachts($id) {
		$old = cmfAdminMenu::getSubMenuId();
		cmfAdminMenu::setSubMenuId($id);
		$this->delete($this->getListId(array('yachts'=>$id)));
		cmfAdminMenu::setSubMenuId($old);
	}

}

?>