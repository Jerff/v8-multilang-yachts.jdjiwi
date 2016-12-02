<?php


class shipyards_yachts_plan_list_controller extends driver_controller_gallery_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_yachts_plan_list_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/yachts/plan/');
		$this->setEditUrl('/admin/shipyards/yachts/plan/');
	}

	public function delete($id) {
		$is = $this->getId()==$id;
		$id = cmfModulLoad('shipyards_yachts_plan_edit_controller')->delete($id);
        if($is) {
		    $this->getResponse()->addRedirect($this->getSubmitUrl(array('id'=>null)));
		}
		return parent::delete($id);
	}

}

?>