<?php


class brokerage_yachts_foto_edit_controller extends driver_controller_gallery_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'brokerage_yachts_foto_edit_modul');

		// url
		$this->setSubmitUrl('/admin/brokerage/yachts/foto/');
		$this->setCatalogUrl('/admin/brokerage/yachts/foto/');
	}

	public function deleteYachts($id) {
		$old = cmfAdminMenu::getSubMenuId();
		cmfAdminMenu::setSubMenuId($id);
		$this->delete($this->getListId(array('yachts'=>$id)));
		cmfAdminMenu::setSubMenuId($old);
	}

}

?>