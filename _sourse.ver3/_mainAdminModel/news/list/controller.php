<?php


class news_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'news_list_modul');

		// url
		$this->setSubmitUrl('/admin/news/');
		$this->setEditUrl('/admin/news/edit/');

	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/news/item/', $arg->uri);
    }

	public function delete($id) {
		$id = cmfModulLoad('news_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>