<?php


class arenda_list_controller extends driver_controller_list_tree {

	protected function init() {
		parent::init();
		$this->addModul('main',	'arenda_list_modul');

		// url
		$this->setSubmitUrl('/admin/arenda/');
		$this->setEditUrl('/admin/arenda/edit/');
		$this->setUrl('yachts', '/admin/arenda/yachts/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/arenda/', $arg->isUri);
    }

	public function filterSection() {
		$filter = cmfModulLoad('arenda_list_db')->getNameList(array('parent'=>0));
		$filter[0]['name'] = 'Все разделы';
		return parent::abstractFilterPart($filter, 'parent', 'start');
	}

	public function delete($id) {
		$id = cmfModulLoad('arenda_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function getYachtsUrl() {
		$opt = array('arenda'=>$this->getIndex());
		return $this->getUrl('yachts', $opt);
	}

}

?>