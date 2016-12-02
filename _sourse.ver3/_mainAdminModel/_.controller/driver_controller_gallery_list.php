<?php


abstract class driver_controller_gallery_list extends driver_controller_list {


	protected function getLimitAll() {
		return array(100, 125, 150, 'all');
	}

    protected function move1($id, $type=null) {
        if(parent::move2($id, $type)) {
            $this->setNewView();
        }
    }

	public function getEditUrl($opt=null) {
		return $this->getUrl('edit', $opt);
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
