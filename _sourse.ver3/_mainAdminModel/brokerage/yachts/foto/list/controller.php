<?php


class brokerage_yachts_foto_list_controller extends driver_controller_gallery_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'brokerage_yachts_foto_list_modul');

		// url
		$this->setSubmitUrl('/admin/brokerage/yachts/foto/');
		$this->setEditUrl('/admin/brokerage/yachts/foto/');
	}

	public function delete($id) {
		$is = $this->getId()==$id;
		$id = cmfModulLoad('brokerage_yachts_foto_edit_controller')->delete($id);
		if($is) {
		    $this->getResponse()->addRedirect($this->getSubmitUrl(array('id'=>null)));
		}
		return parent::delete($id);
	}

}

?>