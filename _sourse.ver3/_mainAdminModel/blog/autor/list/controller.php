<?php


class blog_autor_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'blog_autor_list_modul');

		// url
		$this->setSubmitUrl('/admin/blog/autor/');
		$this->setEditUrl('/admin/blog/autor/edit/');
		$this->setUrl('blog', '/admin/blog/');
	}

    public function getBlogUrl() {
		$opt = array('autor'=>$this->getIndex());
		return $this->getUrl('blog', $opt);
	}

	public function listAutor($id) {
		static $mData = null;
        if(is_null($mData)) {
            $mData = $this->getSql()->placeholder("SELECT autor, count(id) FROM ?t WHERE autor ?@ GROUP BY `autor`", db_blog, $this->getDataId())
								->fetchRowAll(0, 1);
        }
        return (int)get($mData, $id);
	}

	public function delete($id) {
		$id = cmfModulLoad('blog_autor_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>