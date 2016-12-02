<?php


class article_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'article_list_modul');

		// url
		$this->setSubmitUrl('/admin/article/');
		$this->setEditUrl('/admin/article/edit/');

	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/articles/item/', $arg->uri);
    }

	public function delete($id) {
		$id = cmfModulLoad('article_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>