<?php


class foto_list_controller extends driver_controller_gallery_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'foto_list_modul');

		// url
		$this->setSubmitUrl('/admin/foto/list/');
		$this->setEditUrl('/admin/foto/list/');
	}

	public function delete($id) {
        $is = $this->getId()==$id;
		$id = cmfModulLoad('foto_edit_controller')->delete($id);
		if($is) {
		    $this->getResponse()->addRedirect($this->getSubmitUrl(array('id'=>null)));
		}
		return parent::delete($id);
	}

}

?>