<?php


abstract class driver_controller_gallery_edit extends driver_controller_edit {

    protected function init() {
        parent::init();
		$this->callFuncWriteAdd('updatePreview');
	}

	protected function updatePreview() {
	    $this->getModul()->updatePreview();
	    $this->getResponse()->addScript("cmf.admin.gallery.show();");
	}

    public function getPath() {
        return $this->getModul()->getPath();
    }
	public function getSize() {
	    return $this->getModul()->getSize();
	}


}

?>
