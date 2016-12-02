<?php


class selfie_list_controller extends driver_controller_gallery_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'selfie_list_modul');

		// url
		$this->setSubmitUrl('/admin/selfie/list/');
		$this->setEditUrl('/admin/selfie/list/');
	}

	public function delete($id) {
        $is = $this->getId()==$id;
		$id = cmfModulLoad('selfie_edit_controller')->delete($id);
		if($is) {
		    $this->getResponse()->addRedirect($this->getSubmitUrl(array('id'=>null)));
		}
		return parent::delete($id);
	}

}

?>