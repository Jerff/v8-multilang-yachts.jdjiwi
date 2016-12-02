<?php

class blog_edit_controller extends driver_controller_edit {

    protected function init() {
        parent::init();
        $this->addModul('main', 'blog_edit_modul');
        $this->addModul('lang',	'blog_edit_lang_modul');

        // url
        $this->setSubmitUrl('/admin/blog/edit/');
        $this->setCatalogUrl('/admin/blog/');
    }

    public function viewSiteUrl() {
        return parent::viewSiteUrl('/blog/item/', $this->viewSiteData('uri'));
    }

	public function deleteAutor($id) {
		$this->delete($this->getListId(array('autor'=>$id)));
	}

    public function delete($id) {
        $id = parent::delete($id);
        cmfControllerBlog::delete($id);
        return $id;
    }

}

?>