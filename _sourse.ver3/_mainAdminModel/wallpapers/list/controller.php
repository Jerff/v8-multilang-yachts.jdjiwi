<?php


class wallpapers_list_controller extends driver_controller_gallery_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'wallpapers_list_modul');

		// url
		$this->setSubmitUrl('/admin/wallpapers/list/');
		$this->setEditUrl('/admin/wallpapers/list/');
	}

	public function delete($id) {
        $is = $this->getId()==$id;
		$id = cmfModulLoad('wallpapers_edit_controller')->delete($id);
		if($is) {
		    $this->getResponse()->addRedirect($this->getSubmitUrl(array('id'=>null)));
		}
		return parent::delete($id);
	}

}

?>