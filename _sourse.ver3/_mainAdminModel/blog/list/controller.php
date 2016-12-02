<?php


class blog_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'blog_list_modul');

		// url
		$this->setSubmitUrl('/admin/blog/');
		$this->setEditUrl('/admin/blog/edit/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/blog/item/', $arg->uri);
    }

	public function filterAutor() {
		$filter = cmfModulLoad('blog_autor_list_db')->getNameList();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'autor', 'end');
	}

	public function delete($id) {
		$id = cmfModulLoad('blog_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>