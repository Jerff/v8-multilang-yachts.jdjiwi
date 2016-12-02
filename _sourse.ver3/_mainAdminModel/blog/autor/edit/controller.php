<?php

class blog_autor_edit_controller extends driver_controller_edit {

    protected function init() {
        parent::init();
        $this->addModul('main', 'blog_autor_edit_modul');
		$this->addModul('lang',	'blog_autor_edit_lang_modul');

        // url
        $this->setSubmitUrl('/admin/blog/autor/edit/');
        $this->setCatalogUrl('/admin/blog/autor/');
    }

    public function delete($id) {
        $id = parent::delete($id);
        cmfModulLoad('blog_edit_controller')->deleteAutor($id);
        return $id;
    }

}

?>