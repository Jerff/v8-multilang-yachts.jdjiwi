<?php


class main_slider_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'main_slider_list_modul');

		// url
		$this->setSubmitUrl(cmfPages::getMain());
		$this->setEditUrl(cmfPages::getMain());
	}

	protected function getLimit() {
		return 1000;
	}

	public function getEditUrl($opt=null) {
		return $this->getUrl('edit', $opt);
	}

	public function getNew2Url($opt=null) {
        $opt['id'] = null;
		return $this->getUrl('edit', $opt);
	}

	public function delete($id) {
		$is = $this->getId()==$id;
		$id = cmfModulLoad('main_slider_edit_controller')->delete($id);
		if($is) {
		    $this->getResponse()->addRedirect($this->getSubmitUrl(array('id'=>null)));
		}
		return parent::delete($id);
	}

    protected function move1($id, $type=null) {
        if(parent::move2($id, $type)) {
            $this->setNewView();
        }
    }

    protected function move2($id, $type=null) {
        if(parent::move1($id, $type)) {
            $this->setNewView();
        }
	}

	public function getPostMove($data) {
        return (view_gallery::posMove($this->getModulName(), $this->getName(), $this->getKey(), $data->pos));
	}

}

?>