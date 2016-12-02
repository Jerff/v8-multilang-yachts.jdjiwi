<?php


class shipyards_yachts_foto_list_controller extends driver_controller_gallery_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_yachts_foto_list_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/yachts/foto/');
		$this->setEditUrl('/admin/shipyards/yachts/foto/');
	}

	public function delete($id) {
		$is = $this->getId()==$id;
		$id = cmfModulLoad('shipyards_yachts_foto_edit_controller')->delete($id);
		if($is) {
		    $this->getResponse()->addRedirect($this->getSubmitUrl(array('id'=>null)));
		}
		return parent::delete($id);
	}

}

?>